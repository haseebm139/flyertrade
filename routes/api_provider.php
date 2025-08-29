<?php
use App\Http\Controllers\Api\Provider\{ProfileController,ProviderServiceController,BookingController,ChatController,PayoutController};

Route::prefix('provider')->group(function () {


  Route::middleware('auth:sanctum')->group(function () {

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile/{id}', 'show');
        Route::post('/profile', 'store');
        Route::get('me','getProfile');
        Route::get('working-hours','getWorkingHours');
        Route::post('working-hours','createWorkingHours');
        Route::post('/change-availibily-status','changeAvailabilityStatus');
    });
    Route::controller(ProviderServiceController::class)->prefix('service')->group(function () {
        Route::get('/','index');
        Route::post('/','store');
        Route::post('update/{id}','update');
        Route::delete('/{id}','destroy');
    });




















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
