<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\BaseController as BaseController;

use Illuminate\Http\Request;
use App\Models\RentalProducts;
use App\Models\EquipmentType;
use App\Models\TeamUser;
use App\Models\Duration;
use App\Models\Price;
use Carbon\Carbon;

class EquipmentTypesController extends BaseController
{

    public function index($product_id)
    {
        $user = auth()->user();
        $success['equipmentTypes'] = [];
        if ($product_id == 0) {
            $invitedTeams = TeamUser::where('user_id', $user->id)->get();
            $allTeams[0] = $user->current_team_id;
            foreach ($invitedTeams as $invitedTeam) {
                $allTeams[] = $invitedTeam->team_id;
            }
            $products = RentalProducts::whereIn('team_id', $allTeams)->get();
            foreach ($products as $product) {
                foreach ($product->equipmentTypes as $equipmentType) {
                    $success['equipmentTypes'][] = $equipmentType;
                }
            }
        } else {
            $currentproduct = RentalProducts::find($product_id);
            if (!$currentproduct) {
                $responseMessage = "The specified rental product does not exist or is not associated with the current team.";
                return $this->sendError($responseMessage, 500);
            }
            // $equipmentTypes[] = $currentproduct->equipmentTypes;
            foreach ($currentproduct->equipmentTypes as $equipmentType) {
                $success['equipmentTypes'][] = $equipmentType;
            }
        }

        return $this->sendResponse($success, null);
    }

    public function store(Request $request, $product_id)
    {
        $currentproduct = RentalProducts::whereId($product_id)->get();

        if (!$currentproduct) {
            $responseMessage = "The specified rental product does not exist or is not associated with the current team.";
            return $this->sendError($responseMessage, 500);
        }

        $equipmenttype = new EquipmentType();
        $equipmenttype->name = $request->name;
        $equipmenttype->description = $request->description;
        $equipmenttype->min_amount = $request->min_amount;
        $equipmenttype->max_amount = $request->max_amount;
        $equipmenttype->require_min = $request->require_min;

        if ($request->hasFile('widget_image')) {
            $file = $request->file('widget_image');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();
            // Valid File Extensions
            $valid_extension = array("jpg", "jpeg", "jfif", "pjpeg", "pjp", "png");
            // 2MB in Bytes
            $maxFileSize = 10097152;
            // Check file extension
            if (in_array(strtolower($extension), $valid_extension)) {

                // Check file size
                if ($fileSize <= $maxFileSize) {
                    $path = 'uploads/equipmentTypes/';
                    if (!\File::isDirectory(public_path($path))) {
                        \File::makeDirectory($path, 0777, true, true);
                    }

                    $file_name = 'equipement_' . date('dmYHis') . '.' . $extension;
                    $file_name = preg_replace('/\s+/', '', $file_name);

                    //remove image before upload
                    $file->move(public_path($path), $file_name);
                    $file_path = $path . $file_name;
                    $equipmenttype->widget_image = $file_path;
                } else {
                    $responseMessage = 'File too large. File must be less than 2MB.';
                    return $this->sendError($responseMessage, 500);
                }
            }
        } else {
            $equipmenttype->widget_image = null;
        }
        $equipmenttype->widget_display = $request->widget_display;
        $equipmenttype->asset_id = $request->asset_id;
        $equipmenttype->product_id = $product_id;
        $equipmenttype->tax_template = $request->tax_template;
        $equipmenttype->save();

        $durations = Duration::where('product_id', $product_id)->get();
        foreach ($durations as $duration) {
            $prices = new Price();
            $prices->total = 0;
            $prices->deposit = 0;
            $prices->equipment_id = $equipmenttype->id;
            $prices->duration_id = $duration->id;
            $prices->product_id = $product_id;
            $prices->save();
        }

        $responseMessage = "Equipment created successfully.";
        return $this->sendResponse([], $responseMessage);
    }

    public function getById($product_id, $id)
    {
        $equipmentType = EquipmentType::whereId($id)->get()->first();
        if (!$equipmentType) {
            $responseMessage = "The specified rental Equipment Type does not exist or is not associated with the current team.";
            return $this->sendError($responseMessage, 500);
        }
        $success['equipmentType'] = $equipmentType;
        return $this->sendResponse($success, null);
    }

    public function downloadfile($product_id, $id)
    {
        $file = public_path(EquipmentType::whereId($id)->get()->first()->widget_image);
        return response()->download($file);
    }

    public function update(Request $request, $product_id, $id)
    {
        $user = auth()->user();

        $currentproduct = RentalProducts::whereId($id)->get();

        if (!$currentproduct) {
            $responseMessage = "The specified rental product does not exist or is not associated with the current team.";
            return $this->sendError($responseMessage, 500);
        }

        $equipmentTypes = EquipmentType::find($id);
        if (!$equipmentTypes) {
            $responseMessage = "The specified Equipment does not exist or is not associated with the current team.";
            return $this->sendError($responseMessage, 500);
        }

        $equipmentTypes->name = $request->name;
        $equipmentTypes->description = $request->description;
        $equipmentTypes->min_amount = $request->min_amount;
        $equipmentTypes->max_amount = $request->max_amount;
        $equipmentTypes->require_min = $request->require_min;

        if ($request->widget_image_changed == 1) {
            if ($equipmentTypes->widget_image != null) {
                $imagepath = public_path($equipmentTypes->widget_image);
                if (\File::exists($imagepath)) {
                    unlink($imagepath);
                }
            }
            if ($request->hasFile('widget_image')) {
                $file = $request->file('widget_image');
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $tempPath = $file->getRealPath();
                $fileSize = $file->getSize();
                $mimeType = $file->getMimeType();
                // Valid File Extensions
                $valid_extension = array("jpg", "jpeg", "jfif", "pjpeg", "pjp", "png");
                // 2MB in Bytes
                $maxFileSize = 10097152;
                // Check file extension
                if (in_array(strtolower($extension), $valid_extension)) {

                    // Check file size
                    if ($fileSize <= $maxFileSize) {
                        $path = 'uploads/equipmentTypes/';
                        if (!\File::isDirectory(public_path($path))) {
                            \File::makeDirectory($path, 0777, true, true);
                        }

                        $file_name = 'rental_' . date('dmYHis') . '.' . $extension;
                        $file_name = preg_replace('/\s+/', '', $file_name);

                        //remove image before upload
                        $file->move(public_path($path), $file_name);
                        $file_path = $path . $file_name;
                        $equipmentTypes->widget_image = $file_path;
                    } else {
                        $responseMessage = 'File too large. File must be less than 2MB.';
                        return $this->sendError($responseMessage, 500);
                    }
                }
            } else {
                $equipmentTypes->widget_image = null;
            }
        }
        $equipmentTypes->widget_display = $request->widget_display;
        $equipmentTypes->asset_id = $request->asset_id;
        $equipmentTypes->tax_template = $request->tax_template;
        $equipmentTypes->product_id = $product_id;
        $equipmentTypes->save();

        $sucess['equipmentTypes'] = $equipmentTypes;
        $responseMessage = "Current equipmentTypes updated successfully.";
        return $this->sendResponse($sucess, $responseMessage);
    }

    public function destroy($product_id, $id)
    {
        $ids = explode(",", $id);
        foreach ($ids as $imageid) {
            $imageEquipment = EquipmentType::find($imageid);
            if ($imageEquipment->widget_image != null) {
                $imagepath = public_path($imageEquipment->widget_image);
                if (\File::exists($imagepath)) {
                    unlink($imagepath);
                }
            }
        }
        $deleteEquipmentType = EquipmentType::whereIn('id', $ids)->delete();

        if ($deleteEquipmentType == 0) {
            $responseMessage = 'The specified Equipement Type does not exist or is not associated with the current team.';
            return $this->sendError($responseMessage, 500);
        }
        $responseMessage = "Rental Equipement Type deleted successfully.";
        return $this->sendResponse([], $responseMessage);
    }
}
