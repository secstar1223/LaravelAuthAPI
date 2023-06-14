<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\RentalProducts;
use App\Models\Availability;
use App\Http\Controllers\Api\BaseController as BaseController;
use Carbon\Carbon;

class AvailabilityController extends BaseController
{
    public function index($product_id)
    {


        $user = auth()->user();
        if ($product_id == 0) {
            $products = RentalProducts::where('team_id', $user->current_team_id)->get();
            foreach ($products as $product) {
                $availabilities = [];
                if ($product->availabilities !== null) {
                    foreach ($product->availabilities as $available) {
                        $appliesTo = [];
                        foreach ($available->durations as $duration) {
                            $appliesTo[] = [$duration->name, $duration->id];
                        }
                         $success[] = [
                            'availabilities' => $available,
                            'applies_to' => $appliesTo,
                        ];
                    }
                }

            }

        } else {
            $currentproduct = RentalProducts::find($product_id);
            if (!$currentproduct) {
                $responseMessage = "The specified Availability does not exist or is not associated with the current team.";
                return $this->sendError($responseMessage, 500);
            }
            $availabilities = [];
            if ($currentproduct->availabilities !== null) {
                foreach ($currentproduct->availabilities as $available) {
                    $appliesTo = [];
                    foreach ($available->durations as $duration) {
                        $appliesTo[] = [$duration->name, $duration->id];
                    }
                    $success[] = [
                        'availabilities' => $available,
                        'applies_to' => $appliesTo,
                    ];
                }
            }
        }
        return $this->sendResponse($success, null);
    }

    protected function getApplyTo($applies)
    {
        $appliesTo = '';
        $i = 1;
        foreach ($applies as $apply) {
            $appliesTo .= $apply[0];
            if ($i != count($applies)) {
                $appliesTo .= ',';
            }
            $i++;
        }
        return $appliesTo;
    }

    public function store($product_id, Request $request)
    {
        $starts_specifics = array_map('intval', explode(",", $request->starts_specific));
        $starts_specifics = array_map(function ($time) {
            if ($time > 86400) {
                $time -= 86400;
            }

            return $time;
        }, $starts_specifics);

        $availability = new Availability();
        $availability->product_id = $product_id;
        $availability->mon = $request->mon;
        $availability->tue = $request->tue;
        $availability->wed = $request->wed;
        $availability->thu = $request->thu;
        $availability->fri = $request->fri;
        $availability->sat = $request->sat;
        $availability->sun = $request->sun;
        $availability->times = $request->times;
        $availability->starts_every = ($request->times == 'specific' ? 0 : $request->starts_every);
        $availability->start_time = ($request->times == 'specific' ? $starts_specifics[0] : $request->start_time);
        $availability->end_time = ($request->times == 'specific' ? $starts_specifics[count($starts_specifics) - 1] : $request->end_time);
        $availability->starts_specific = $starts_specifics;
        $availability->save();

        $durations = RentalProducts::find($product_id)->durations()->whereIn('id', array_map('intval', explode(",", $request->durations)))->get();
        foreach ($durations as $duration) {
            $availability->durations()->attach($duration);
        }
        $responseMessage = "Availavility created successfully.";
        return $this->sendResponse([], $responseMessage);
    }

    public function getById($product_id, $id)
    {
        $currentAvailability = Availability::find($id);
        if (!$currentAvailability) {
            $responseMessage = "The specified rental Equipment Type does not exist or is not associated with the current team.";
            return $this->sendError($responseMessage, 500);
        }


        $appliesTo = [];
        foreach ($currentAvailability->durations as $duration) {
            $appliesTo[] = [$duration->name, $duration->id];
        }
        $success['availability'] = [
            'availabilities' => $currentAvailability,
            'applies_to' => $appliesTo,
        ];
        return $this->sendResponse($success, null);
    }

    public function update(Request $request, $product_id, $id)
    {
        $currentproduct = RentalProducts::whereId($product_id)->get();
        if (!$currentproduct) {
            $responseMessage = "The specified Availability does not exist or is not associated with the current team.";
            return $this->sendError($responseMessage, 500);
        }
        $starts_specifics = array_map('intval', explode(",", $request->starts_specific));
        $starts_specifics = array_map(function ($time) {
            if ($time > 86400) {
                $time -= 86400;
            }
            return $time;
        }, $starts_specifics);
        $availability = Availability::find($id);
        $availability->product_id = $product_id;
        $availability->mon = $request->mon;
        $availability->tue = $request->tue;
        $availability->wed = $request->wed;
        $availability->thu = $request->thu;
        $availability->fri = $request->fri;
        $availability->sat = $request->sat;
        $availability->sun = $request->sun;
        $availability->times = $request->times;
        $availability->starts_every = ($request->times == 'specific' ? 0 : $request->starts_every);
        $availability->start_time = ($request->times == 'specific' ? $starts_specifics[0] : $request->start_time);
        $availability->end_time = ($request->times == 'specific' ? $starts_specifics[count($starts_specifics) - 1] : $request->end_time);
        $availability->starts_specific = $starts_specifics;
        $availability->save();
        $currentDetail = RentalProducts::with('availabilities.durations')->where('team_id', 1)->first();
        $durations = RentalProducts::find($product_id)->durations()->whereIn('id', array_map('intval', explode(",", $request->durations)))->get();
        foreach ($durations as $duration) {
            \DB::table('availability_duration')->where(['availability_id' => $availability->id, 'duration_id' => $duration->id])->delete();
            $availability->durations()->save($duration);
        }
        $responseMessage = "Availavility updated successfully.";
        return $this->sendResponse([], $responseMessage);
    }

    public function destroy($product_id, $id)
    {
        $deleteAvailability = Availability::destroy($id);
        if ($deleteAvailability == 0) {
            $responseMessage = 'The specified Availability does not exist or is not associated with the current';
            return $this->sendError($responseMessage, 500);
        }
        $responseMessage = "Availability Type deleted successfully.";
        return $this->sendResponse([], $responseMessage);
    }
}
