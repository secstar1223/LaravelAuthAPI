<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;

use App\Models\RentalProducts;
use App\Models\EquipmentType;
use App\Models\Duration;
use App\Models\Price;
use App\Models\RentalQuestions;
use App\Models\TeamUser;
use Carbon\Carbon;

class RentalQuestionController extends BaseController
{
    public function index($product_id)
    {
        $user = auth()->user();
        $success['rental_questions'] = [];
        if ($product_id == 0) {
            $invitedTeams = TeamUser::where('user_id', $user->id)->get();
            $allTeams[0] = $user->current_team_id;
            foreach ($invitedTeams as $invitedTeam) {
                $allTeams[] = $invitedTeam->team_id;
            }
            $products = RentalProducts::whereIn('team_id', $allTeams)->get();
            foreach ($products as $product) {
                if ($product->rentalQuestions != []) {
                    foreach ($product->rentalQuestions as $rentalQuestion) {
                        $success['rental_questions'][] = $rentalQuestion;
                    }
                }
            }
        } else {
            $currentproduct = RentalProducts::find($product_id);
            if (!$currentproduct) {
                $responseMessage = "The specified rental product does not exist or is not associated with the current team.";
                return $this->sendError($responseMessage, 500);
            }
            foreach ($currentproduct->rentalQuestions as $rentalQuestion) {
                $success['rental_questions'][] = $rentalQuestion;
            }
        }
        return $this->sendResponse($success, null);
    }

    public function store(Request $request, $product_id)
    {
        $currentproduct = RentalProducts::whereId($product_id)->get();

        if (!$currentproduct) {
            $responseMessage = "The specified Rental Product does not exist or is not associated with the current team.";
            return $this->sendError($responseMessage, 500);
        }

        $rentalQuestion = new RentalQuestions();
        $rentalQuestion->product_id = $product_id;
        $rentalQuestion->question_id = $request->question_id;
        $rentalQuestion->is_require = $request->is_require;
        $rentalQuestion->is_internal = $request->is_internal;
        $rentalQuestion->is_display = $request->is_display;
        $rentalQuestion->is_checked = $request->is_checked;
        $rentalQuestion->save();
       
        $responseMessage = "Selected Question added successfully.";
        return $this->sendResponse([], $responseMessage);
    }

    public function getById($product_id, $id)
    {
        $rentalQuestion = RentalQuestions::find($id);
        if (!$rentalQuestion) {
            $responseMessage = "The specified Rental Question does not exist or is not associated with the current team.";
            return $this->sendError($responseMessage, 500);
        }
        $success['rental_question'] =$rentalQuestion;
        return $this->sendResponse($success, null);
    }

    public function update(Request $request, $product_id, $id)
    {
        $currentproduct = RentalProducts::whereId($product_id)->get();
        if (!$currentproduct) {
            $responseMessage = "The specified Duration does not exist or is not associated with the current team.";
            return $this->sendError($responseMessage, 500);
        }
        $rentalQuestion = RentalQuestions::find($id);
        if (!$rentalQuestion) {
            $responseMessage = "The specified Rental Question does not exist or is not associated with the current team.";
            return $this->sendError($responseMessage, 500);
        }
        $rentalQuestion->product_id = $product_id;
        $rentalQuestion->question_id = $request->question_id;
        $rentalQuestion->is_require = $request->is_require;
        $rentalQuestion->is_internal = $request->is_internal;
        $rentalQuestion->is_display = $request->is_display;
        $rentalQuestion->is_checked = $request->is_checked;
        $rentalQuestion->save();

        $responseMessage = "Rental Question updated successfully.";
        return $this->sendResponse([], $responseMessage);
    }

    public function destroy($product_id, $id)
    {
        $ids = explode(",", $id);
        $deleteRentalQuestions = RentalQuestions::whereIn('id', $ids)->delete();
        if ($deleteRentalQuestions == 0) {
            $responseMessage = 'The specified Rental Question does not exist or is not associated with the current Rental Product.';
            return $this->sendError($responseMessage, 500);
        }
        $responseMessage = "Rental Question remove successfully.";
        return $this->sendResponse([], $responseMessage);

    }
}
