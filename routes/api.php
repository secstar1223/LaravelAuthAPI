<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TeamsController;
use App\Http\Controllers\Api\RentalProductsController;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\EquipmentTypesController;
use App\Http\Controllers\Api\DurationsController;
use App\Http\Controllers\Api\AvailabilityController;

Route::group([
    // 'middleware' => 'CORS',
], function ($router) {

    //Auth Routes
    Route::post('/verify-email', [AuthController::class, 'verify']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/email-verification', [AuthController::class, 'sendVerification']);

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout']);

    Route::post('/reset-password', [AuthController::class, 'reset']);
    Route::post('/forgot-password', [AuthController::class, 'forgot']);
});
Route::group([
    'middleware' => 'auth.jwt',
], function ($router) {
    // User Routes
    Route::get('/user', [UserController::class, 'index']);
    Route::post('/user', [UserController::class, 'update']);
    Route::post('/update-password', [UserController::class, 'updatePassword']);

    // Team Routes
    Route::get('/team', [TeamsController::class, 'index']);
    Route::post('/team', [TeamsController::class, 'store']);
    Route::get('/team/{team_id}/edit', [TeamsController::class, 'edit']);
    Route::post('/team/{team_id}', [TeamsController::class, 'update']);
    Route::delete('/team/{team_id}', [TeamsController::class, 'destroy']);

    Route::get('/team-invitation', [TeamsController::class, 'getTeamInvitations']);
    Route::post('/team-invitation', [TeamsController::class, 'sendTeamInvitaion']);
    Route::delete('/team-invitation/{invitation_id}', [TeamsController::class, 'cancelTeamInvitation']);


    Route::put('/team-invitation-confirm/{hash}', [TeamsController::class, 'confirmTeamInvitaion']);
    Route::get('/team-invited', [TeamsController::class, 'getTeamInvited']);


    Route::put('/team-switch/{team_id}', [TeamsController::class, 'switchTeam']);
    Route::get('/team-member', [TeamsController::class, 'getTeamMembers']);
    Route::delete('/team-member/{member_id}', [TeamsController::class, 'removeTeamMember']);

    Route::get('/constants', [TeamsController::class, 'getConstants']);

    // Rentals Routes
    Route::get('/rentals', [RentalProductsController::class, 'index']);
    Route::post('/rentals', [RentalProductsController::class, 'store']);
    Route::get('/rentals/{rental_id}', [RentalProductsController::class, 'getById']);
    Route::get('/rentals-download/{url}', [RentalProductsController::class, 'downloadfile']);
    Route::post('/rentals/{product}', [RentalProductsController::class, 'update']);
    Route::delete('/rentals/{product}', [RentalProductsController::class, 'destroy']);

    // Assets Routes
    Route::get('/assets', [AssetController::class, 'index']);
    Route::post('/assets', [AssetController::class, 'store']);
    Route::get('/assets/{asset_id}', [AssetController::class, 'getById']);
    Route::post('/assets/{assets}', [AssetController::class, 'update']);
    Route::delete('/assets/{assets}', [AssetController::class, 'destroy']);

    Route::prefix('/rentals/{product}')->group(function () {

        Route::get('/durations', [DurationsController::class, 'index']);
        Route::post('/durations', [DurationsController::class, 'store']);
        Route::get('/durations/{durations}', [DurationsController::class, 'getById']);
        Route::post('/durations/{durations}', [DurationsController::class, 'update']);
        Route::delete('/durations/{durtaions}', [DurationsController::class, 'destroy']);

        Route::get('/equipment-types', [EquipmentTypesController::class, 'index']);
        Route::post('/equipment-types', [EquipmentTypesController::class, 'store']);
        Route::get('/equipment-types-download/{url}', [EquipmentTypesController::class, 'downloadfile']);
        Route::get('/equipment-types/{equipmenttype}', [EquipmentTypesController::class, 'getById']);
        Route::post('/equipment-types/{equipmenttype}', [EquipmentTypesController::class, 'update']);
        Route::delete('/equipment-types/{equipmenttype}', [EquipmentTypesController::class, 'destroy']);

        Route::get('/availability', [AvailabilityController::class, 'index']);
        Route::post('/availability', [AvailabilityController::class, 'store']);
        Route::get('/availability/{availID}', [AvailabilityController::class, 'getById']);
        Route::post('/availability/{availID}', [AvailabilityController::class, 'update']);
        Route::delete('/availability/{availID}', [AvailabilityController::class, 'destroy']);
    });

});
