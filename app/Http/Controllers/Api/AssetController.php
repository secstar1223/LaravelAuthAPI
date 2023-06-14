<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Asset;
use Carbon\Carbon;

class AssetController extends BaseController
{
    public function index()
    {
        $user = auth()->user();
        $success['assets'] = Asset::where('team_id', $user->current_team_id)->get();
        return $this->sendResponse($success, null);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $newAsset = new Asset();
        $newAsset->name = $request->name;
        $newAsset->amount = $request->amount;
        $newAsset->resource_tracking = $request->resource_tracking;
        $newAsset->team_id = $user->current_team_id;
        $newAsset->save();
        $responseMessage = "Asset created successfully.";
        return $this->sendResponse([], $responseMessage);
    }

    public function getById($id)
    {
        $asset = Asset::whereId($id)->get()->first();
        if (!$asset) {
            $responseMessage = "The specified Asset does not exist or is not associated with the current team.";
            return $this->sendError($responseMessage, 500);
        }
        $success['asset'] = $asset;
        return $this->sendResponse($success, null);
    }


    public function update(Request $request, $id)
    {
        $currentAsset = Asset::find($id);
        if (!$currentAsset) {
            $responseMessage = 'The specified Asset does not exist or is not associated with the current team.';
            return $this->sendError($responseMessage, 500);
        }
        Asset::whereId($id)->update([
            'name' => $request->name,
            'amount' => $request->amount,
            'resource_tracking' => $request->resource_tracking,
        ]);

        $currentAsset->name = $request->name;
        $currentAsset->amount = $request->amount;
        $currentAsset->resource_tracking = $request->resource_tracking;
        $currentAsset->save();
        $success['assets'] = $currentAsset;
        $responseMessage = "Current Asset updated successfully.";
        return $this->sendResponse($success, $responseMessage);
    }

    public function destroy($id)
    {
        $ids = explode(",", $id);
        $deleteAsset = Asset::whereIn('id', $ids)->delete();
        if ($deleteAsset == 0) {
            $responseMessage = 'The specified Asset does not exist or is not associated with the current team.';
            return $this->sendError($responseMessage, 500);
        }
        $responseMessage = "Asset deleted successfully.";
        return $this->sendResponse([], $responseMessage);
    }

}
