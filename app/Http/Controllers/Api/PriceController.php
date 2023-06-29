<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\BaseController as BaseController;


use Illuminate\Http\Request;

use App\Models\RentalProducts;
use App\Models\EquipmentType;
use App\Models\Price;
use App\Models\Duration;
use App\Models\TeamUser;

use Carbon\Carbon;

class PriceController extends BaseController
{
    protected function getDetail($price): array
    {

        return [
            'id' => $price->id,
            'duration' => Duration::find($price->duration_id),
            'equipment' => EquipmentType::find($price->equipment_id),
            'total' => $price->total,
            'deposit' => $price->deposit,
            'created_at' => $price->created_at,
            'updated_at' => $price->updated_at,
        ];
    }

    public function index($product_id, $equipment_id)
    {
        $user = auth()->user();
        $success['prices'] = [];
        if ($product_id == 0) {
            $invitedTeams = TeamUser::where('user_id', $user->id)->get();
            $allTeams[0] = $user->current_team_id;
            foreach ($invitedTeams as $invitedTeam) {
                $allTeams[] = $invitedTeam->team_id;
            }
            $products = RentalProducts::whereIn('team_id', $allTeams)->get();
            foreach ($products as $product) {
                if ($product->prices != []) {
                    if ($equipment_id == 0) {
                        foreach ($product->prices as $price) {
                            $success['prices'][] = $this->getDetail($price);
                        }
                    } else {
                        $prices = Price::where(['equipment_id' => $equipment_id])->get();
                        foreach ($prices as $price) {
                            $success['prices'][] = $this->getDetail($price);
                        }
                    }
                }
            }
        } else {
            $currentproduct = RentalProducts::find($product_id);
            if (!$currentproduct) {
                $responseMessage = "The specified rental product does not exist or is not associated with the current team.";
                return $this->sendError($responseMessage, 500);
            }
            if ($equipment_id == 0) {
                foreach ($currentproduct->prices as $price) {
                    $success['prices'][] = $this->getDetail($price);
                }
            } else {
                $prices = Price::where(['product_id' => $product_id, 'equipment_id' => $equipment_id])->get();
                foreach ($prices as $price) {
                    $success['prices'][] = $this->getDetail($price);
                }
            }
        }
        return $this->sendResponse($success, null);
    }

    public function store(Request $request, $product_id, $equipment_id)
    {
        $prices = new Price();
        $prices->total = $request->total;
        $prices->deposit = $request->deposit;
        $prices->equipment_id = $equipment_id;
        $prices->duration_id = $request->duration_id;
        $prices->product_id = $product_id;
        $prices->save();
        $responseMessage = "Price created successfully.";
        return $this->sendResponse($prices, $responseMessage);
    }

    public function getById($product_id, $equipment_id, $id)
    {
        $price = Price::find($id);
        if (!$price) {
            $responseMessage = "The specified Price does not exist or is not associated with the current team.";
            return $this->sendError($responseMessage, 500);
        }
        $success['price'] = $this->getDetail($price);
        return $this->sendResponse($success, null);
    }

    public function update(Request $request, $product_id, $equipment_id, $id)
    {

        // $currentproduct = RentalProducts::whereId($product_id)->get();

        // if (!$currentproduct) {
        //     $responseMessage = "The specified rental product does not exist or is not associated with the current team.";
        //     return $this->sendError($responseMessage, 500);
        // }

        $price = Price::find($id);
        if (!$price) {
            $responseMessage = "The specified Price does not exist or is not associated with the current team.";
            return $this->sendError($responseMessage, 500);
        }

        $price->total = $request->total;
        $price->deposit = $request->deposit;
        // $price->equipment_id = $equipment_id;
        // $price->duration_id = $request->duration_id;
        // $price->product_id = $product_id;
        $price->save();
        $sucess['price'] = $price;
        $responseMessage = "Current Price updated successfully.";
        return $this->sendResponse($sucess, $responseMessage);
    }

    public function destroy($product_id, $equipment_id, $id)
    {
        $ids = explode(",", $id);
        $deletePrice = Price::whereIn('id', $ids)->delete();
        if ($deletePrice == 0) {
            $responseMessage = 'The specified Price does not exist or is not associated with the current Rental Product.';
            return $this->sendError($responseMessage, 500);
        }
        $responseMessage = "Price Type deleted successfully.";
        return $this->sendResponse([], $responseMessage);
    }
}
