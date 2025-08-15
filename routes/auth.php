<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\HomeController;


Route::prefix('auth')->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::post('register','register' );
        Route::post('login', 'login');
        Route::post('guest', 'guestLogin');
        Route::post('{proverder}/login','socialLogin');
        Route::post('facebook_login', 'facebookLogin');
        Route::post('apple_login', 'appleLogin');
        Route::post('send-code-to-email', 'sendCodeToEmail');
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('location', 'updateLocation');
            Route::post('logout', 'logout');
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
