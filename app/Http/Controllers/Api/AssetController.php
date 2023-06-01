<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\User;
use App\Models\Teams;
use App\Models\TeamInvitation;
use App\Models\TeamUser;
use App\Models\Assets;
use Carbon\Carbon;


class AssetController extends BaseController
{
    public function index()
    {
       $user = auth()->user();
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        
    }

    public function edit(Asset $asset)
    {
        
    }

    public function update(Request $request, Asset $asset)
    {
    
    }

    public function destroy(Asset $asset)
    {

    }
       
}
