<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TeamsController;
use App\Http\Controllers\Api\RentalProductsController;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\EquipmentTypesController;
use App\Http\Controllers\Api\DurationsController;
use App\Http\Controllers\Api\AvailabilityController;
use App\Http\Controllers\Api\PriceController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\RentalQuestionController;

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
    Route::get('/rental', [RentalProductsController::class, 'index']);
    Route::post('/rental', [RentalProductsController::class, 'store']);
    Route::get('/rental/{rental_id}', [RentalProductsController::class, 'getById']);
    Route::get('/rental-download/{url}', [RentalProductsController::class, 'downloadfile']);
    Route::post('/rental/{product}', [RentalProductsController::class, 'update']);
    Route::delete('/rental/{product}', [RentalProductsController::class, 'destroy']);

    // Assets Routes
    Route::get('/asset', [AssetController::class, 'index']);
    Route::post('/asset', [AssetController::class, 'store']);
    Route::get('/asset/{asset_id}', [AssetController::class, 'getById']);
    Route::post('/asset/{asset_id}', [AssetController::class, 'update']);
    Route::delete('/asset/{asset_id}', [AssetController::class, 'destroy']);

    // Questions Routes
    Route::get('/question', [QuestionController::class, 'index']);
    Route::post('/question', [QuestionController::class, 'store']);
    Route::get('/question/{question_id}', [QuestionController::class, 'getById']);
    Route::post('/question/{question_id}', [QuestionController::class, 'update']);
    Route::delete('/question/{question_id}', [QuestionController::class, 'destroy']);

    Route::prefix('/rental/{product_id}')->group(function () {

        Route::get('/duration', [DurationsController::class, 'index']);
        Route::post('/duration', [DurationsController::class, 'store']);
        Route::get('/duration/{duration_id}', [DurationsController::class, 'getById']);
        Route::post('/duration/{duration_id}', [DurationsController::class, 'update']);
        Route::delete('/duration/{duration_id}', [DurationsController::class, 'destroy']);

        Route::get('/equipment', [EquipmentTypesController::class, 'index']);
        Route::post('/equipment', [EquipmentTypesController::class, 'store']);
        Route::get('/equipment-download/{url}', [EquipmentTypesController::class, 'downloadfile']);
        Route::get('/equipment/{equipment_id}', [EquipmentTypesController::class, 'getById']);
        Route::post('/equipment/{equipment_id}', [EquipmentTypesController::class, 'update']);
        Route::delete('/equipment/{equipment_id}', [EquipmentTypesController::class, 'destroy']);

        Route::get('/availability', [AvailabilityController::class, 'index']);
        Route::post('/availability', [AvailabilityController::class, 'store']);
        Route::get('/availability/{availability_id}', [AvailabilityController::class, 'getById']);
        Route::post('/availability/{availability_id}', [AvailabilityController::class, 'update']);
        Route::delete('/availability/{availability_id}', [AvailabilityController::class, 'destroy']);

        Route::get('/question', [RentalQuestionController::class, 'index']);
        Route::post('/question', [RentalQuestionController::class, 'store']);
        Route::get('/question/{question_id}', [RentalQuestionController::class, 'getById']);
        Route::post('/question/{question_id}', [RentalQuestionController::class, 'update']);
        Route::delete('/question/{question_id}', [RentalQuestionController::class, 'destroy']);

        Route::prefix('/equipment/{equipment_id}')->group(function () {
            Route::get('/price', [PriceController::class, 'index']);
            Route::post('/price', [PriceController::class, 'store']);
            Route::get('/price/{price_id}', [PriceController::class, 'getById']);
            Route::post('/price/{price_id}', [PriceController::class, 'update']);
            Route::delete('/price/{price_ids}', [PriceController::class, 'destroy']);
        });
    });

});
