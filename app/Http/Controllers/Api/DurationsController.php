<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\RentalProducts;
use App\Models\Duration;
use App\Http\Controllers\Api\BaseController as BaseController;
use Carbon\Carbon;

class DurationsController extends BaseController
{
    protected function timestampToParts(int $duration)
    {
        $days = floor($duration / 86400);
        $hours = floor(($duration - ($days * 86400)) / 3600);
        $minutes = floor(($duration - ($days * 86400) - ($hours * 3600)) / 60);
        return [
            'days' => $days,
            'hours' => $hours,
            'minutes' => $minutes,
        ];
    }

    protected function partsToTimestamp(array $parts): int
    {
        return $parts['days'] * 86400 + $parts['hours'] * 3600 + $parts['minutes'] * 60;
    }

    protected function getDuration($duration) :array
    {
        $parts = $this->timestampToParts($duration->duration);
        return [
            'id' => $duration->id,
            'product_id' => $duration->product_id,
            'name' => $duration->name,
            'days' => $parts['days'],
            'hours' => $parts['hours'],
            'minutes' => $parts['minutes'],
            'buffer'=> $duration->buffer,
        ];
    }

    public function index($product_id)
    {
        $user = auth()->user();
        $success['durations'] = [];
        if ($product_id == 0) {
            $products = RentalProducts::where('team_id', $user->current_team_id)->get();
            foreach ($products as $product) {
                if ($product->durations != []) {
                    // $success['durations'][] = $product->durations;
                    foreach($product->durations as $duration){
                        $success['durations'][]= $duration;
                    }
                }
            }

        } else {
            $currentproduct = RentalProducts::find($product_id);
            if (!$currentproduct) {
                $responseMessage = "The specified rental product does not exist or is not associated with the current team.";
                return $this->sendError($responseMessage, 500);
            }
            foreach($currentproduct->durations as $duration){
                $success['durations'][]= $this->getDuration($duration);
            }
        }

        return $this->sendResponse($success, null);
    }

    public function store(Request $request, $product_id)
    {
        $currentproduct = RentalProducts::whereId($product_id)->get();

        if (!$currentproduct) {
            $responseMessage = "The specified Duration does not exist or is not associated with the current team.";
            return $this->sendError($responseMessage, 500);
        }

        $duration = new Duration();
        $duration->name = $request->name;
        $duration->duration = $this->partsToTimestamp(['days' => $request->days, 'hours' => $request->hours, 'minutes' => $request->minutes]);
        $duration->product_id = $product_id;
        $duration->buffer = $request->buffer;
        $duration->save();

        $responseMessage = "Duration created successfully.";
        return $this->sendResponse([], $responseMessage);
    }

    public function getById($product_id, $id)
    {
        $duration = Duration::whereId($id)->get()->first();
        if (!$duration) {
            $responseMessage = "The specified Duration does not exist or is not associated with the current team.";
            return $this->sendError($responseMessage, 500);
        }
        $success['duration'] = $this->getDuration($duration);
        return $this->sendResponse($success, null);
    }

    public function update(Request $request, $product_id, $id)
    {
        $currentproduct = RentalProducts::whereId($product_id)->get();
        if (!$currentproduct) {
            $responseMessage = "The specified Duration does not exist or is not associated with the current team.";
            return $this->sendError($responseMessage, 500);
        }
        $duration = Duration::find($id);
        if (!$duration) {
            $responseMessage = "The specified Duration does not exist or is not associated with the current team.";
            return $this->sendError($responseMessage, 500);
        }
        $duration->name = $request->name;
        $duration->duration = $this->partsToTimestamp(['days' => $request->days, 'hours' => $request->hours, 'minutes' => $request->minutes]);
        $duration->product_id = $product_id;
        $duration->buffer = $request->buffer;
        $duration->save();

        $responseMessage = "Duration updated successfully.";
        return $this->sendResponse([], $responseMessage);
    }

    public function destroy($product_id, $id)
    {
        $ids = explode(",", $id);
        $deleteDuration = Duration::whereIn('id', $ids)->delete();
        if ($deleteDuration == 0) {
            $responseMessage = 'The specified Duration does not exist or is not associated with the current Rental Product.';
            return $this->sendError($responseMessage, 500);
        }
        $responseMessage = "Duration Type deleted successfully.";
        return $this->sendResponse([], $responseMessage);

    }
}
