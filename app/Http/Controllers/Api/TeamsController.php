<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Teams;
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
        $success['teams'] = Teams::where('user_id',$user->id)->get();
        return $this->sendResponse($success, 'Teams retrieved successfully.');
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
                'name'              => explode(' ', $request->name, 2)[0] . "'s Team",
                'user_id'           => $user->id,
                'email'             => $request->email,
                'phone'             => $request->phone,
                'date_join'         => Carbon::now(),
                'bank'              => $request->bank,
                'bank_route'        => $request->bank_route,
                'front_percent'     => $request->front_percent,
                'back_percent'      => $request->back_percent,
                'address'           => $request->address,
                'website'           => $request->website,
                'city'              => $request->city,
                'state'             => $request->state,
                'zip'               => $request->zip,
                'country'           => $request->country,
                'timezone'          => $request->timezone,
                'currency'          => $request->currency,
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
    public function update(Request $request, $id)
    {

        $currentTeam = Teams::find($id);
        if (!$currentTeam) {
            $responseMessage = 'The specified Team does not exist or is not associated with the current team.';
            return $this->sendError($responseMessage, 500);
        }

        $team = Teams::whereId($id)->update([
            'name'              => explode(' ', $request->name, 2)[0] . "'s Team",
            'phone'             => $request->phone,
            'bank'              => $request->bank,
            'bank_route'        => $request->bank_route,
            'front_percent'     => $request->front_percent,
            'back_percent'      => $request->back_percent,
            'address'           => $request->address,
            'website'           => $request->website,
            'city'              => $request->city,
            'state'             => $request->state,
            'zip'               => $request->zip,
            'country'           => $request->country,
            'timezone'          => $request->timezone,
            'currency'          => $request->currency,
            'cc_disputes_email' => $request->cc_disputes_email,
        ]);
        $currentTeam = Teams::find($id);
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
}
