<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use App\Models\Teams;
use Validator;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $success['user'] = $user;
        $success['current_team'] = Teams::whereId($user->current_team_id)->get()->first();
        $success['teams'] = User::find(1)->teams;
        $responseMessage = null;
        return $this->sendResponse($success, $responseMessage);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $user->name = $request->name;
        $user->save();
        $sucess['user'] = $user;
        $responseMessage = "Profile updated successfully.";
        return $this->sendResponse($sucess, $responseMessage);
    }

    public function updatePassword(Request $request)
    {
        $current_user = auth()->user();
        if (!(Hash::check($request->current_password, $current_user->password))) {
            // The passwords matches
            $responseMessage = 'Your current password does not matches with the password you provided. Please try again.';
            return $this->sendError($responseMessage, 500);
        }

        if (strcmp($request->current_password, $request->new_password) == 0) {
            //Current password and new password are same
            $responseMessage = 'New Password cannot be same as your current password. Please choose a different password.';
            return $this->sendError($responseMessage, 500);
        }
        if (strcmp($request->new_password, $request->confirm_password) !== 0) {
            //Current password and new password are same
            $responseMessage = "Conform password doesn't match.";
            return $this->sendError($responseMessage, 500);
        }

        $current_user->password = Hash::make($request->new_password);
        $current_user->save();
        $responseMessage = "Password updated Successfully.";
        return $this->sendResponse([], $responseMessage);
    }
}
