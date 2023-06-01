<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\User;
use App\Models\Teams;
use App\Models\TeamInvitation;
use App\Models\TeamUser;
use Carbon\Carbon;
use Validator;

class TeamsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $success['teams'] = Teams::where('user_id', $user->id)->get();
        $success['current_team'] = Teams::whereId($user->current_team_id)->get();
        return $this->sendResponse($success, 'Teams retrieved successfully.');
    }
    public function getConstants()
    {
        $user = auth()->user();
        $success['countries'] = \Config::get('constants.countries');
        $success['timezones'] = \Config::get('constants.timezones');
        $success['currencies'] = \Config::get('constants.currencies');
        $success['roles'] = \Config::get('constants.roles');
        $responseMessage = 'The constants was retrieved successfully.';
        return $this->sendResponse($success, $responseMessage);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        try {
            $team = new Teams([
                'name' => explode(' ', $request->name, 2)[0] . "'s Team",
                'user_id' => $user->id,
                'email' => $request->email,
                'phone' => $request->phone,
                'date_join' => Carbon::now(),
                'bank' => $request->bank,
                'bank_route' => $request->bank_route,
                'front_percent' => $request->front_percent,
                'back_percent' => $request->back_percent,
                'address' => $request->address,
                'website' => $request->website,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
                'country' => $request->country,
                'timezone' => $request->timezone,
                'currency' => $request->currency,
                'cc_disputes_email' => $request->cc_disputes_email,
            ]);
            $team->save();
            $responseMessage = "Team created successfully.";
            return $this->sendResponse([], $responseMessage);
        } catch (\Exception $e) {
            $responseMessage = $e;
            // $responseMessage = 'Team not created';
            return $this->sendError($responseMessage, 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $team = Teams::find($id);

        if (is_null($team)) {
            $responseMessage = 'Team not found.';
            return $this->sendError($responseMessage, 404);
        }
        $sucess['team'] = $team;
        $responseMessage = "Team retrieved successfully.";
        return $this->sendResponse($sucess, $responseMessage);
    }
    public function create($request)
    {
    }

    public function edit($id)
    {
        $team = Teams::find($id);
        if (is_null($team)) {
            $responseMessage = 'Team not found.';
            return $this->sendError($responseMessage, 404);
        }
        $sucess['team'] = $team;
        $responseMessage = "Team retrieved successfully.";
        return $this->sendResponse($sucess, $responseMessage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $team_id)
    {

        $currentTeam = Teams::find($team_id);
        if (!$currentTeam) {
            $responseMessage = 'The specified Team does not exist or is not associated with the current team.';
            return $this->sendError($responseMessage, 500);
        }
        $team = Teams::whereId($team_id)->update([
            'name' => $request->name || explode(' ', $request->name, 2)[0] . "'s Team",
            'phone' => $request->phone,
            'bank' => $request->bank,
            'bank_route' => $request->bank_route,
            'front_percent' => $request->front_percent,
            'back_percent' => $request->back_percent,
            'address' => $request->address,
            'website' => $request->website,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => $request->country,
            'timezone' => $request->timezone,
            'currency' => $request->currency,
            'cc_disputes_email' => $request->cc_disputes_email,
        ]);
        $currentTeam = Teams::find($team_id);
        $sucess['team'] = $currentTeam;
        $responseMessage = "Current Team updated successfully.";
        return $this->sendResponse($sucess, $responseMessage);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleteTeam = Teams::destroy($id);
        if ($deleteTeam == 0) {
            $responseMessage = 'The specified Team does not exist or is not associated with the current team.';
            return $this->sendError($responseMessage, 500);
        }
        $responseMessage = "Team deleted successfully.";
        return $this->sendResponse([], $responseMessage);
    }
    public function sendTeaminvitaion(Request $request)
    {
        $user = auth()->user();
        if ($user->email == $request->email) {
            $responseMessage = "This user already belongs to the team.";
            return $this->sendError($responseMessage, 500);
        }
        $teamMember = User::where('email', $request->email)->get()->first();
        if ($teamMember != null) {
            $isExistInvited = TeamUser::where(['team_id' => $user->current_team_id, 'user_id' => $teamMember->id])->get()->first();
            if ($isExistInvited) {
                $responseMessage = "This user already belongs to the team.";
                return $this->sendError($responseMessage, 500);
            }
        }
        $teamInvitation = TeamInvitation::where(['team_id' => $user->current_team_id, 'email' => $request->email])->get()->first();
        if ($teamInvitation) {
            $responseMessage = "You have already sent this email, send again?";
            return $this->sendResponse([], $responseMessage);
        }
        try {
            $teamInvitation = new TeamInvitation([
                'team_id' => $user->current_team_id,
                'email' => $request->email,
                'role' => $request->role,
            ]);
            $teamInvitation->save();

            $frontUrl = env("FRONTEND_URL", "");
            $currnet_team = Teams::whereId($user->current_team_id)->get()->first()->name;
            if ($teamMember != null) {
                $acceptUrl = $frontUrl . '/team-invitation/' . $user->current_team_id . '/' . $request->email;
                \Mail::to($request->email)->send(new \App\Mail\TeamInvitation($currnet_team, $acceptUrl, null));
            } else {
                $registerUrl = $frontUrl . '/register';
                \Mail::to($request->email)->send(new \App\Mail\TeamInvitation($currnet_team, null, $registerUrl));
            }
            $responseMessage = $user->name . " has invited you to collaborate on the " . $currnet_team . "'s team";
            return $this->sendResponse([], $responseMessage);
        } catch (\Exception $e) {
            $responseMessage = 'Not sent email invited.';
            return $this->sendError($responseMessage, 500);
        }
    }
    public function cancelTeamInvitation(Request $request, $id)
    {
        $deleteTeam = TeamInvitation::destroy($id);
        if ($deleteTeam == 0) {
            $responseMessage = 'The specified Team Invitation does not exist or is not associated with the current team.';
            return $this->sendError($responseMessage, 500);
        }
        $responseMessage = "The selected Team Invitation deleted successfully.";
        return $this->sendResponse([], $responseMessage);
    }
    public function getTeamInvitations(Request $request)
    {
        $user = auth()->user();
        $success['teaminvitation'] = TeamInvitation::where('team_id', $user->current_team_id)->get();
        return $this->sendResponse($success, 'Team Invitations retrieved successfully.');
    }

    public function switchTeam(Request $request)
    {
        $user = auth()->user();
        $existTeam = Teams::find($request->team_id);
        if (!$existTeam) {
            $responseMessage = 'The specified Team does not exist or is not associated with the current team.';
            return $this->sendError($responseMessage, 500);
        }
        User::whereId($user->id)->update([
            'current_team_id' => $request->team_id,
        ]);
        $currentTeam = Teams::find($request->team_id);
        $sucess['team'] = $currentTeam;
        $responseMessage = "Current Team updated successfully.";
        return $this->sendResponse($sucess, $responseMessage);
    }

    public function getTeammembers(Request $request)
    {
        $user = auth()->user();
        $teammembers = TeamUser::where('team_id', $user->current_team_id)->get();
        $success['teammembers'] = [];
        foreach ($teammembers as $teammember) {
            $success['teammembers'][] = [
                'user' => TeamUser::find($teammember->id)->user()->get(),
                'role' => $teammember->role,
            ];
        }
        $success['current_team'] = Teams::whereId($user->current_team_id)->get()->first();
        $responseMessage = 'Teams retrieved successfully.';
        return $this->sendResponse($success, $responseMessage);
    }
    public function removeTeamMember(Request $request, $id)
    {
        $user = auth()->user();
        $deleteTeamUser = TeamUser::destroy($id);
        if ($deleteTeamUser == 0) {
            $responseMessage = 'The specified User does not exist or is not associated with the current User.';
            return $this->sendError($responseMessage, 500);
        }
        $responseMessage = "Team User deleted successfully.";
        return $this->sendResponse([], $responseMessage);
    }

    public function setTeamMember(Request $request)
    {
        try {
            $teamMember = User::where('email', $request->email)->get()->first();
            $teaminvitation = TeamInvitation::where(['team_id' => $request->team_id, 'email' => $request->email])->get()->first();
            if ($teaminvitation == null) {
                $responseMessage = "Doesn't exist email invited.";
                return $this->sendError($responseMessage, 500);
            }
            TeamInvitation::destroy($teaminvitation->id);
            $teamuser = new TeamUser([
                'team_id' => $request->team_id,
                'user_id' => $teamMember->id,
                'role' => $teaminvitation->role,
            ]);
            $teamuser->save();
            $responseMessage = 'Team member invited successfully.';
            return $this->sendResponse([], $responseMessage);
        } catch (\Exception $e) {
            $responseMessage = 'Not email invited.';
            return $this->sendError($responseMessage, 500);
        }
    }
}
