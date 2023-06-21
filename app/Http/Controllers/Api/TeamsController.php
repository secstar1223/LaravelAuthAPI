<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\User;
use App\Models\Teams;
use App\Models\TeamInvitation;
use App\Models\TeamUser;
use Carbon\Carbon;

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
        $success['invitedTeam'] = \DB::table('team_user')
            ->leftJoin('teams', 'team_user.team_id', '=', 'teams.id')
            ->where('team_user.user_id', '=', $user->current_team_id)
            ->get();
        return $this->sendResponse($success, null);
    }
    public function getConstants()
    {
        $user = auth()->user();
        $success['countries'] = \Config::get('constants.countries');
        $success['timezones'] = \Config::get('constants.timezones');
        $success['currencies'] = \Config::get('constants.currencies');
        $success['roles'] = \Config::get('constants.roles');
        $success['times'] = \Config::get('constants.times');
        return $this->sendResponse($success, null);
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
                'name' => $request->name ? $request->name : $user->name . "' team",
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
            $responseMessage = $e->getMessage();
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
        return $this->sendResponse($sucess, null);
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
        return $this->sendResponse($sucess, null);
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
            'name' => $request->name,
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
    public function sendTeamInvitaion(Request $request)
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
            $hash = bin2hex(random_bytes(64));
            $teamInvitation = new TeamInvitation([
                'team_id' => $user->current_team_id,
                'email' => $request->email,
                'role' => $request->role,
                'hash' => $hash,
            ]);
            $teamInvitation->save();

            $frontUrl = env("FRONTEND_URL", "");
            $currnet_team = Teams::whereId($user->current_team_id)->get()->first()->name;
            if ($teamMember != null) {
                $acceptUrl = $frontUrl . '/team-invitation/' . $hash;
                \Mail::to($request->email)->send(new \App\Mail\TeamInvitation($currnet_team, $acceptUrl, null));
            } else {
                $registerUrl = $frontUrl . '/register';
                \Mail::to($request->email)->send(new \App\Mail\TeamInvitation($currnet_team, null, $registerUrl));
            }
            $responseMessage = "Invitation sent to " . $request->email;
            return $this->sendResponse([], $responseMessage);
        } catch (\Exception $e) {
            $responseMessage = $e->getMessage();
            return $this->sendError($responseMessage, 500);
        }
    }
    public function cancelTeamInvitation(Request $request, $id)
    {
        try {
            $deleteTeam = TeamInvitation::destroy($id);
            if ($deleteTeam == 0) {
                $responseMessage = 'The specified Team Invitation does not exist or is not associated with the current team.';
                return $this->sendError($responseMessage, 500);
            }
            $responseMessage = "The selected Team Invitation deleted successfully.";
            return $this->sendResponse([], $responseMessage);
        } catch (\Exception $e) {
            $responseMessage = $e->getMessage();
            return $this->sendError($responseMessage, 500);
        }
    }
    public function getTeamInvitations(Request $request) //pending Invitation
    {
        try {
            $user = auth()->user();
            $success['teamInvitations'] = TeamInvitation::where('team_id', $user->current_team_id)->get();
            return $this->sendResponse($success, null);
        } catch (\Exception $e) {
            $responseMessage = $e->getMessage();
            return $this->sendError($responseMessage, 500);
        }
    }

    public function getTeamInvited(Request $request)
    {
        try {
            $user = auth()->user();
            $success['invitations'] = TeamInvitation::where('email', $user->email)->get();
            return $this->sendResponse($success, null);
        } catch (\Exception $e) {
            $responseMessage = $e->getMessage();
            return $this->sendError($responseMessage, 500);
        }
    }

    public function switchTeam(Request $request, $team_id)
    {
        $user = auth()->user();
        $existTeam = Teams::find($team_id);
        if (!$existTeam) {
            $responseMessage = 'The specified Team does not exist or is not associated with the current team.';
            return $this->sendError($responseMessage, 500);
        }
        User::whereId($user->id)->update([
            'current_team_id' => $team_id,
        ]);
        $currentTeam = Teams::find($team_id);
        $sucess['team'] = $currentTeam;
        $responseMessage = "Current Team updated successfully.";
        return $this->sendResponse($sucess, $responseMessage);
    }

    public function getTeamMembers(Request $request)
    {
        $user = auth()->user();
        $invitedTeams = TeamUser::where('user_id',$user->id)->get();
        $allTeams[0] = $user->current_team_id;
        foreach($invitedTeams as $invitedTeam){
                $allTeams []= $invitedTeam->team_id;
        }

        $teams = TeamUser::whereIn('team_id', $allTeams)->get();
        $success['teamMembers'] = [];
        $teamMembers = [];
        foreach ($teams as $team) {
            if($team->user_id!=$user->id){
                $teamMembers [] = TeamUser::find($team->id)->user()->first();
            }
        }
        $success['teamMembers'] = [
            'user' => $teamMembers,
            'role' => TeamUser::where('team_id',$user->current_team_id)->first()->role,
        ];

        $success['current_team'] = Teams::whereId($user->current_team_id)->get()->first();
        $responseMessage = null;
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

    public function confirmTeamInvitaion(Request $request, $hash)
    {
        try {
            $teaminvitation = TeamInvitation::where('hash', $hash)->get()->first();
            if ($teaminvitation == null) {
                $responseMessage = "Doesn't exist email invited.";
                return $this->sendError($responseMessage, 500);
            }
            $teamMember = User::where('email', $teaminvitation->email)->get()->first();
            TeamInvitation::destroy($teaminvitation->id);
            $teamuser = new TeamUser([
                'team_id' => $teaminvitation->team_id,
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
