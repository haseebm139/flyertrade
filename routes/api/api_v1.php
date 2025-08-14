<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\HomeController;



Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('guest', [AuthController::class, 'guestLogin']);
        Route::post('{proverder}/login', [AuthController::class, 'socialLogin']);
        Route::post('facebook_login', [AuthController::class, 'facebookLogin']);
        Route::post('apple_login', [AuthController::class, 'appleLogin']);
        Route::post('send-code-to-email', [AuthController::class, 'sendCodeToEmail']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('location', [AuthController::class, 'updateLocation']);
            Route::post('logout', [AuthController::class, 'logout']);
        });
    });
    Route::middleware('auth:sanctum')->group(function () {

        Route::controller(HomeController::class)->group(function () {
            Route::get('/services', 'services');
        });
    });
    Route::middleware(['auth:sanctum', 'role:customer'])->get('/customer/profile', [CustomerController::class, 'profile']);


    Route::middleware(['auth:sanctum', 'role:provider'])->get('/provider/profile', [ProviderController::class, 'profile']);
});
