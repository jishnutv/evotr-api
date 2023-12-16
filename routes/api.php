<?php

use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoterAuthController;
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

    Route::controller(ElectionController::class)->group(function () {
        Route::get('/elections', 'index');
        Route::get('/elections/{id}', 'show');
        Route::post('/create-election', 'create');
        Route::delete('/delete-election/{id}', 'destroy');
    });

    Route::controller(CandidateController::class)->group(function () {
        Route::get('/candidates/{id}', 'index');
        // Route::get('/candidates/{id}', 'show');
        Route::post('/create-candidate', 'create');
        Route::delete('/delete-candidate/{id}', 'destroy');
    });
});
