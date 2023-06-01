<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TeamsController;

Route::group([
    // 'middleware' => 'CORS',
], function ($router) {

    //Auth Routes
    Route::post('/verify-email', [AuthController::class, 'verify'])->name('user.verify');
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/email-verification', [AuthController::class, 'sendverification']);

    Route::post('/register', [AuthController::class, 'register'])->name('register.user');
    Route::post('/login', [AuthController::class, 'login'])->name('login.user');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout.user');

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
    Route::post('/team', [TeamsController::class, 'store'])->name('team.store');
    Route::get('/team/{team_id}/edit', [TeamsController::class, 'edit'])->name('team.edit');
    Route::post('/team/{team_id}', [TeamsController::class, 'update'])->name('team.update');
    Route::delete('/team/{team_id}', [TeamsController::class, 'destroy'])->name('team.delete');

    Route::get('/team-invitation', [TeamsController::class, 'getTeamInvitations']);
    Route::post('/team-invitation', [TeamsController::class, 'sendTeaminvitaion']);
    Route::delete('/team-invitation/{invitation_id}', [TeamsController::class, 'cancelTeamInvitation']);

    Route::post('/team-switch', [TeamsController::class, 'switchTeam']);

    Route::get('/team-member', [TeamsController::class, 'getTeammembers']);
    Route::post('/team-member-invited', [TeamsController::class, 'setTeammember']);
    Route::delete('/team-member/{member_id}', [TeamsController::class, 'removeTeamMember']);

    Route::get('/constants', [TeamsController::class, 'getConstants']);


});
