<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TeamsController;

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


    Route::post('/team-switch', [TeamsController::class, 'switchTeam']);
    Route::get('/team-member', [TeamsController::class, 'getTeamMembers']);
    Route::delete('/team-member/{member_id}', [TeamsController::class, 'removeTeamMember']);

    Route::get('/constants', [TeamsController::class, 'getConstants']);

    // Assets Routes
    Route::get('/asset', [AssetController::class, 'index']);
    Route::get('/asset/create', [AssetController::class, 'create']);
    Route::post('/asset', [AssetController::class, 'store']);
    Route::get('/asset/{asset}/edit', [AssetController::class, 'edit']);
    Route::put('/asset/{asset}', [AssetController::class, 'update']);
    Route::delete('/asset/{asset}', [AssetController::class, 'destroy']);


});
