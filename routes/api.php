<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StripeWebhookController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ConversationsController;
use App\Http\Controllers\Api\MessagesController;
use App\Http\Controllers\Api\OffersController;


Route::post('stripe/webhook', [StripeWebhookController::class, 'handle']);

Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::post('register','register');
        Route::post('login', 'login');
        Route::post('guest', 'guestLogin');
        Route::post('{proverder}/login','socialLogin');
        Route::post('facebook_login', 'facebookLogin');
        Route::post('apple_login', 'appleLogin');
        Route::post('send-code-to-email', 'sendCodeToEmail');
        Route::post('update-password', 'updatePassword');
    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('location', [AuthController::class,'updateLocation']);
        Route::post('logout', [AuthController::class,'logout']);
        Route::post('/change-password', [AuthController::class,'changePassword']);
    });


    Route::middleware('auth:sanctum')->group(function () {

        Route::controller(HomeController::class)->group(function () {
            Route::get('/services', 'services');
        });
    });

    // Chat & Offers API
    Route::middleware('auth:sanctum')->group(function () {
        // Conversations
        Route::get('/conversations', [ConversationsController::class, 'index']);
        Route::post('/conversations', [ConversationsController::class, 'store']);

        // Messages
        Route::get('/conversations/{conversationId}/messages', [MessagesController::class, 'index']);
        Route::post('/conversations/{conversationId}/messages', [MessagesController::class, 'store']);

        // Offers
        Route::post('/conversations/{conversationId}/offers', [OffersController::class, 'create']);
        Route::post('/offers/{offerId}/respond', [OffersController::class, 'respond']);
        Route::post('/offers/{offerId}/finalize', [OffersController::class, 'finalize']);
    });

// require __DIR__ .'/auth.php';
require __DIR__.'/api_customer.php';
require __DIR__.'/api_provider.php';
