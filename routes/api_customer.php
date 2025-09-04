<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Customer\{AuthController,ProviderController,ProfileController,BookingController as CustomerBookingController,ChatController,ReviewController,PaymentController};
use App\Http\Controllers\Api\Shared\MediaController;

Route::prefix('customer')->group(function () {


    Route::middleware('auth:sanctum')->group(function () {
        Route::controller(ProviderController::class)->group(function () {
            Route::get('/providers', 'providers');
            Route::get('/providers/{provider}', 'show');
            Route::get('/bookmarks', 'bookmarks');
            Route::post('/bookmarks', 'toggle');
        });

        Route::controller(CustomerBookingController::class)->prefix('booking')->group(function () {
            Route::post('/', 'store');          // create & pay
            Route::get('{booking}','show');    // show one
        });  


        
















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

 