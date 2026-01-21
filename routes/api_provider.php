<?php
use App\Http\Controllers\Api\Provider\{ProfileController,ProviderServiceController,BookingController as ProviderBookingActionController,ChatController,PayoutController};
use App\Http\Controllers\Api\PaymentController;

Route::middleware('auth:sanctum')->controller(ProviderBookingActionController::class)->prefix('booking')->group(function () {
    Route::post('{booking}/accept', 'accept');
    Route::post('{booking}/reject', 'reject');
    Route::post('{booking}/complete', 'complete');
    Route::post('{booking}/start', 'start'); 
    Route::post('provider/direct-store', 'directStore'); // Direct create & accept without payment
    Route::get('provider/job', 'job');
    Route::get('provider/upcoming', 'upcoming');
    Route::get('provider/ongoing', 'ongoing');
    Route::get('provider/completed', 'completed');
    Route::get('provider/pending', 'pending');
    Route::get('provider/total-amount', 'totalAmount');

});
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

     






     
    // Chat
    Route::get('bookings/{booking}/messages', [ChatController::class,'index']);
    Route::post('bookings/{booking}/messages', [ChatController::class,'store']);

    // Payouts
    Route::get('payouts', [PayoutController::class,'index']);
    Route::post('payouts/request', [PayoutController::class,'requestPayout']);

    // Provider can also manage their cards (same controller as customer)
    Route::prefix('payments')->group(function () {
        Route::post('cards', [PaymentController::class,'addCard']);
        Route::get('cards', [PaymentController::class,'listCards']);
        Route::post('cards/{card}/default', [PaymentController::class,'makeDefault']);
        // TEST ONLY: Create test payment_method
        Route::post('test/create-payment-method', [PaymentController::class,'createTestPaymentMethod']);
    });

  });
});
