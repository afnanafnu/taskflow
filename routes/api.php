<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    Route::post('/register', [
        AuthController::class,
        'register',
    ])->middleware('throttle:auth');

    Route::post('/login', [
        AuthController::class,
        'login',
    ])->middleware('throttle:auth');

    /*
    |--------------------------------------------------------------------------
    | Authenticated API
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [
            AuthController::class,
            'logout',
        ]);

        Route::get('/user', [
            AuthController::class,
            'user',
        ]);
    });
});