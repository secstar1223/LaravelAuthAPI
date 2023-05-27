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
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::post('/user', [UserController::class, 'update'])->name('user.update');
    Route::post('/update-password', [UserController::class, 'updatePassword'])->name('user.updatePassword');

    // Team Routes
    Route::get('/team', [TeamsController::class, 'index'])->name('team.index');
    Route::post('/team', [TeamsController::class, 'store'])->name('team.store');
    Route::get('/team/{team}/edit', [TeamsController::class, 'edit'])->name('team.edit');
    Route::post('/team/{team}', [TeamsController::class, 'update'])->name('team.update');
    Route::delete('/team/{team}', [TeamsController::class, 'destroy'])->name('team.delete');

});
