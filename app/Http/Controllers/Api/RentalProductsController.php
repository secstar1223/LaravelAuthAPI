<?php

namespace App\Http\Controllers\Api;

use App\Models\TeamUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\RentalProducts;
use Carbon\Carbon;

class RentalProductsController extends BaseController
{
    public function index()
    {
        $user = auth()->user();
        // $teams = TeamUser::where('');
        $invitedTeams = TeamUser::where('user_id',$user->id)->get();
        $allTeams[0] = $user->current_team_id;
        foreach($invitedTeams as $invitedTeam){
                $allTeams []= $invitedTeam->team_id;
        }

        $products = RentalProducts::whereIn('team_id', $allTeams)->get();
        foreach ($products as $product) {
            $product->equipmentTypes;
            $product->prices;
            $product->durations;
            $product->availabilities;
        }
        $success['products'] = $products;
        return $this->sendResponse($success, null);
    }
    public function getById($product_id)
    {
        $product = RentalProducts::find($product_id);
        if (!$product) {
            $responseMessage = "The specified rental product does not exist or is not associated with the current team.";
            return $this->sendError($responseMessage, 500);
        }
        $product->equipmentTypes;
        $product->prices;
        $product->durations;
        $product->availabilities;
        $success['product'] = $product;
        return $this->sendResponse($success, null);
    }

    public function downloadfile($id)
    {
        $file = public_path(RentalProducts::whereId($id)->get()->first()->image);
        return response()->download($file);
    }

    public function editnewrentals(Request $request)
    {
        $user = auth()->user();
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $rentalProduct = new RentalProducts();
        $rentalProduct->name = $request->name;
        $rentalProduct->description = $request->description;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
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
                    $path = 'uploads/rentals/';
                    if (!\File::isDirectory(public_path($path))) {
                        \File::makeDirectory($path, 0777, true, true);
                    }

                    $file_name = 'rental_' . date('dmYHis') . '.' . $extension;
                    $file_name = preg_replace('/\s+/', '', $file_name);

                    //remove image before upload
                    $file->move(public_path($path), $file_name);
                    $file_path = $path . $file_name;
                    $rentalProduct->image = $file_path;
                } else {
                    $responseMessage = 'File too large. File must be less than 2MB.';
                    return $this->sendError($responseMessage, 500);
                }
            }
        } else {
            $rentalProduct->image = null;
        }
        $rentalProduct->tax_template = $request->tax_template;
        $rentalProduct->team_id = $user->current_team_id;
        $rentalProduct->save();
        $responseMessage = "Rental Product created successfully.";
        return $this->sendResponse([], $responseMessage);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $currentProduct = RentalProducts::find($id);
        if (!$currentProduct) {
            $responseMessage = 'The specified rental product does not exist or is not associated with the current team.';
            return $this->sendError($responseMessage, 500);
        }

        $currentProduct->name = $request->name;
        $currentProduct->description = $request->description;
        $currentProduct->team_id = $user->current_team_id;

        if ($request->image_changed == 1) {
            if ($currentProduct->image != null) {
                $imagepath = public_path($currentProduct->image);
                if (\File::exists($imagepath)) {
                    unlink($imagepath);
                }
            }
            if ($request->hasFile('image')) {
                $file = $request->file('image');
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
                        $path = 'uploads/rentals/';
                        if (!\File::isDirectory(public_path($path))) {
                            \File::makeDirectory($path, 0777, true, true);
                        }

                        $file_name = 'rental_' . date('dmYHis') . '.' . $extension;
                        $file_name = preg_replace('/\s+/', '', $file_name);

                        //remove image before upload
                        $file->move(public_path($path), $file_name);
                        $file_path = $path . $file_name;
                        $currentProduct->image = $file_path;
                    } else {
                        $responseMessage = 'File too large. File must be less than 2MB.';
                        return $this->sendError($responseMessage, 500);
                    }
                }
            } else {
                $currentProduct->image = null;
            }
        }
        $currentProduct->save();
        $sucess['product'] = $currentProduct;
        $responseMessage = "Current Rental Product updated successfully.";
        return $this->sendResponse($sucess, $responseMessage);
    }

    public function destroy($id)
    {
        $ids = explode(",", $id);
        foreach ($ids as $imageid) {
            $imageProduct = RentalProducts::find($imageid);
            if ($imageProduct->image != null) {
                $imagepath = public_path($imageProduct->image);
                if (\File::exists($imagepath)) {
                    unlink($imagepath);
                }
            }
        }
        $deleteProduct = RentalProducts::whereIn('id', $ids)->delete();

        if ($deleteProduct == 0) {
            $responseMessage = 'The specified Rental Product does not exist or is not associated with the current team.';
            return $this->sendError($responseMessage, 500);
        }
        $responseMessage = "Rental Product deleted successfully.";
        return $this->sendResponse([], $responseMessage);
    }
}
