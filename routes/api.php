<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\VoterAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::controller(VoterAuthController::class)->group(function () {
        Route::post('/login', 'login');
    });

    Route::controller(UserController::class)->group(function () {
        // Route::middleware(['jwt.auth'])->group(function () {
            Route::get('/voters', 'getAllVoters');
            Route::get('/voters/{id}', 'getVoter');
            Route::put('/update-voter/{id}', 'voterUpdate');
            Route::put('/change-password/{id}', 'voterUpdatePassword');
        // });
        Route::post('/voter-register', 'voterRegister');
    });
});
