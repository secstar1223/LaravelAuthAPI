<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\ResetPassword;
use App\Models\Teams;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Mail;



class AuthController extends BaseController
{

    protected $user;

    public function __construct()
    {
        $this->middleware("auth:api", ["except" => ["login", "register", "forgot", "reset", "verify", "sendverification"]]);
        $this->user = new User;
    }

    public function login(Request $request)
    {
        $credentials = $request->only(["email", "password"]);
        $user = User::where('email', $credentials['email'])->get()->first();

        if ($user) {
            if (!Auth::attempt($credentials)) {
                $responseMessage = "Invalid username or password";
                return $this->sendError($responseMessage, [], 422);
            }
            $TTL = 24 * 60; //minutes
            \JWTAuth::factory()->setTTL($TTL);
            $customClaims = [
                'user' => $user,
            ];
            $token = \JWTAuth::claims($customClaims)->fromUser($user);
            $success['accessToken'] = $token;
            $success['token_type'] = 'bearer';
            $responseMessage = "Login Successful";

            return $this->sendResponse($success, $responseMessage);
        } else {
            $responseMessage = "Sorry, this user does not exist";
            return $this->sendError($responseMessage, [], 422);
        }
    }

    public function register(Request $req)
    {
        $user = User::where('email', $req->email)->first();
        if ($user) {
            $responseMessage = "Email already exists";
            return $this->sendError($responseMessage, [], 422);
        }
        $user = User::where('name', $req->name)->first();
        if ($user) {
            $responseMessage = "Username already exists";
            return $this->sendError($responseMessage, [], 422);
        }
        $frontUrl = env("FRONTEND_URL", "");
        $hash = Hash::make($req->password);
        $hashh = bin2hex(random_bytes(64));
        $link = $frontUrl . '/verify-email/' . $hashh;

        $user = new User([
            'name' => $req->name,
            'email' => $req->email,
            'password' => $hash,
            'hash' => $hashh,
            'current_team_id' => 0,
        ]);
        \Mail::to($req->email)->send(new \App\Mail\VerificationEmail($req->username, $link));
        $user->save();
        $team = $this->createTeam($user);
        $user->current_team_id = $team->id;
        $user->save();
        $responseMessage = "Register Successful!";
        return $this->sendResponse([], $responseMessage);
    }

    public function verify(Request $req)
    {
        $user = User::where('hash', $req->hash)->first();
        if ($user) {
            $user->email_verified_at = Carbon::now();
            $user->save();
            $success['user'] = $user;
            $responseMessage = "Verification Successful!";
            return $this->sendResponse($success, $responseMessage);
        } else {
            $responseMessage = "Failed Verification, Send again!";
            return $this->sendError($responseMessage, 500);
        }
    }

    public function forgot(Request $request)
    {
        $frontUrl = env("FRONTEND_URL", "");
        $hash = bin2hex(random_bytes(64));
        $link = $frontUrl . '/reset-password/' . $hash;
        \Mail::to($request->email)->send(new \App\Mail\ResetPasswordEmail($link));
        $user = User::where('email', $request->email)->get()->first();
        if (!$user) {
            $responseMessage = 'Email Not Found ';
            return $this->sendError($responseMessage, 404);
        }
        $reset_user = ResetPassword::where('email', $request->email)->get()->first();
        if ($reset_user) {
            ResetPassword::where('email', $request->email)->update(['token' => $hash]);
            $reset = ResetPassword::where('email', $request->email)->get()->first();
        } else {
            $reset = new ResetPassword([
                'email' => $request->email,
                'token' => $hash
            ]);
            $reset->save();
        }
        $responseMessage = 'Reset Password Link sent.';
        return $this->sendResponse([], $responseMessage);
    }

    public function reset(Request $req)
    {
        $reset = ResetPassword::where('token', $req->token)->get()->last();
        if ($reset) {
            $user = User::where('email', $reset->email)->get()->first();
            $user->password = Hash::make($req->password);
            $user->save();
            $responseMessage = 'Password Changed';
            return $this->sendResponse('', $responseMessage);
        }
        $responseMessage = 'Invaild token to reset your password.';
        return $this->sendError($responseMessage, 404);
    }

    private function createTeam(User $user)
    {
        $team = new Teams([
            'name'          => explode(' ', $user->name, 2)[0],
            'user_id'       => $user->id,
            'email'         => $user->email,
            'date_join'     => Carbon::now(),
            'website'       => 'http://bookings247.co',
        ]);
        $team->save();
        return $team;
    }
    public function sendVerification(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->get()->first();
            if ($user) {
                $frontUrl = env("FRONTEND_URL", "");
                $hash = bin2hex(random_bytes(64));
                $link = $frontUrl . '/verify-email/' . $hash;
                $user->hash = $hash;
                $user->save();
                \Mail::to($user->email)->send(new \App\Mail\VerificationEmail($user->name, $link));
                $responseMessage = 'Verification Link Sent';
                return $this->sendResponse('', $responseMessage);
            }
            $responseMessage = 'Email Not Found ';
            return $this->sendError($responseMessage, 404);
        } catch (\Exception $e) {
            $responseMessage = 'Failed to send Verification link';
            return $this->sendError($responseMessage, 500);
        }
    }

    public function refresh()
    {
        $myTTL = 24 * 60; //minutes
        \JWTAuth::factory()->setTTL($myTTL);
        $user = auth('api')->user();
        $customClaims = [
            'user' => $user,
        ];
        $success['accessToken'] = auth()->refresh($customClaims);
        $success['token_type'] = 'bearer';
        $responseMessage = "Refresh Token Successful.";
        return $this->sendResponse($success, $responseMessage);
    }
    public function logout()
    {
        $user = Auth::guard("api")->user()->token();
        $user->revoke();
        $responseMessage = "successfully logged out";
        return $this->sendResponse([], $responseMessage);
    }
}
