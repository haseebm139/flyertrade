<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Customer\{AuthController,ProfileController,BookingController,ChatController,ReviewController,PaymentController};
use App\Http\Controllers\Api\Shared\MediaController;

Route::prefix('customer')->group(function () {

  Route::post('auth/register', [AuthController::class,'register']);
  Route::post('auth/login',    [AuthController::class,'login']);
  Route::post('auth/logout',   [AuthController::class,'logout'])->middleware('auth:sanctum');

  Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', [AuthController::class,'me']);
    Route::put('profile', [ProfileController::class,'update']);
    Route::post('devices', [AuthController::class, 'storeDeviceToken']);

    Route::get('categories', [ProfileController::class,'categories']);    // browse
    Route::get('services',   [ProfileController::class,'services']);

    // Bookings
    Route::apiResource('bookings', BookingController::class)->only(['index','store','show']);
    Route::post('bookings/{booking}/cancel', [BookingController::class,'cancel']);
    Route::post('bookings/{booking}/confirm', [BookingController::class,'confirmCompletion']);

    // Chat
    Route::get('bookings/{booking}/messages', [ChatController::class,'index']);
    Route::post('bookings/{booking}/messages', [ChatController::class,'store']);

    // Reviews
    Route::post('bookings/{booking}/review', [ReviewController::class,'store']);

    // Payments (Stripe)
    Route::post('payments/intent', [PaymentController::class,'createPaymentIntent']);
    Route::post('payments/confirm', [PaymentController::class,'confirmPayment']); // optional server confirm
  });

  // Webhooks (Stripe)
  Route::post('webhooks/stripe', [PaymentController::class,'webhook'])->name('webhooks.stripe');
});

