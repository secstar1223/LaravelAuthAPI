<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\BaseController as BaseController;

use App\Models\MultipleChoice;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\RentalProducts;
use App\Models\EquipmentType;
use App\Models\TeamUser;
use App\Models\Duration;
use App\Models\Price;
use Carbon\Carbon;

class QuestionController extends BaseController
{

    public function index()
    {
        $user = auth()->user();
        $questions = Question::get();
        $success['questions'] = [];
        foreach($questions as $question){
            $question->multipleChoice;
            $success['questions'][] = $question;
        }
        return $this->sendResponse($success, null);
    }

    public function store(Request $request,)
    {

        $question = new Question();
        $question->question = $request->question;
        $question->question_type = $request->question_type;
        $question->question_answer = $request->question_answer;
        $question->save();

        if($request->multiple_choices !=null)
        {
            $choices = explode(",", $request->multiple_choices);
            foreach ($choices as $choice) {
                $multichoice = new MultipleChoice();
                $multichoice->choice = $choice;
                $multichoice->question_id = $question->id;
                $multichoice->save();
            }
        }


        $responseMessage = "Question created successfully.";
        return $this->sendResponse([], $responseMessage);
    }

    public function getById($id)
    {
        $question = Question::whereId($id)->get()->first();
        if (!$question) {
            $responseMessage = "The specified Question does not exist";
            return $this->sendError($responseMessage, 500);
        }
        $question->multipleChoice;
        $success['question'] = $question;
        return $this->sendResponse($success, null);
    }

    public function update(Request $request, $product_id, $id)
    {
        $user = auth()->user();

        $currentquestion = Question::whereId($id)->get();

        if (!$currentquestion) {
            $responseMessage = "The specified Question does not exist or is not associated with the current team.";
            return $this->sendError($responseMessage, 500);
        }

        $question = Question::find($id);
        $question->question = $request->question;
        $question->question_type = $request->question_type;
        $question->question_answer = $request->question_answer;
        $question->save();

        if($request->multiple_choices !=null)
        {
            $choices = explode(",", $request->multiple_choices);
            foreach ($choices as $choice) {
                $multichoice = new MultipleChoice();
                $multichoice->choice = $choice;
                $multichoice->question_id = $question->id;
                $multichoice->save();
            }
        }
        $sucess['question'] = $question;
        $responseMessage = "Current Question updated successfully.";
        return $this->sendResponse($sucess, $responseMessage);
    }

    public function destroy($id)
    {
        $ids = explode(",", $id);
        $deleteQuestion = Question::whereIn('id', $ids)->delete();

        if ($deleteQuestion == 0) {
            $responseMessage = 'The specified Question does not exist or is not associated with the current team.';
            return $this->sendError($responseMessage, 500);
        }
        $responseMessage = "Rental Question deleted successfully.";
        return $this->sendResponse([], $responseMessage);
    }
}
