<?php
use App\Http\Controllers\Api\Provider\{AuthController,ProfileController,BookingController,ChatController,PayoutController};

Route::prefix('api/provider')->group(function () {
  Route::post('auth/register', [AuthController::class,'register']);
  Route::post('auth/login',    [AuthController::class,'login']);

  Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', [AuthController::class,'me']);
    Route::put('profile', [ProfileController::class,'update']);
    Route::post('devices', [AuthController::class, 'storeDeviceToken']);
    Route::post('location', [ProfileController::class,'updateLocation']); // live tracking

    // Booking actions
    Route::get('bookings', [BookingController::class,'index']); // available + my bookings
    Route::post('bookings/{booking}/accept', [BookingController::class,'accept']);
    Route::post('bookings/{booking}/status', [BookingController::class,'updateStatus']); // en_route, in_progress, completed

    // Chat
    Route::get('bookings/{booking}/messages', [ChatController::class,'index']);
    Route::post('bookings/{booking}/messages', [ChatController::class,'store']);

    // Payouts
    Route::get('payouts', [PayoutController::class,'index']);
    Route::post('payouts/request', [PayoutController::class,'requestPayout']);
  });
});

