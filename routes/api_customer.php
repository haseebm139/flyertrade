<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Customer\{AuthController,ProviderController,ProfileController,BookingController as CustomerBookingController,ChatController,ReviewController,NotificationController};
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\Shared\MediaController;


Route::middleware('auth:sanctum')->controller(CustomerBookingController::class)->prefix('booking')->group(function () {
    Route::post('/', 'store');          // create & pay
    Route::get('{booking}','show');    // show one
    
    Route::post('{booking}/cancel', 'cancel');
    Route::post('{booking}/reschedule/request','requestReschedule');    // show one
    Route::post('{booking}/reschedule/respond','respondReschedule');    // show one
    Route::get('customer/pending', 'pending');
    Route::get('customer/upcoming', 'upcoming');    
    Route::get('customer/ongoing', 'ongoing');
    Route::get('customer/completed', 'completed');
    Route::get('customer/cancelled', 'cancelled');
    Route::post('{booking_id}/customer/payment', 'processPayment');
    
    // Late provider actions
    Route::get('{booking}/check-late', 'checkProviderLate');
    Route::post('{booking}/late-action', 'handleLateAction');
});
Route::prefix('customer')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile/{id}', 'show');
            Route::post('/profile', 'store');
            Route::get('/me', 'getProfile');
        });

        Route::controller(ProviderController::class)->group(function () {
            Route::get('/providers', 'providers');
            Route::get('/providers/services/{service_id}', 'providersByServices');
            Route::get('/providers/{provider}', 'show');
            Route::get('/bookmarks', 'bookmarks');
            Route::post('/bookmarks', 'toggle');
        });




        // Chat
        Route::get('bookings/{booking}/messages', [ChatController::class,'index']);
        Route::post('bookings/{booking}/messages', [ChatController::class,'store']);

        // Reviews
        Route::post('bookings/{booking}/review', [ReviewController::class,'store']);

        // Payments (Stripe)
        Route::post('payments/intent', [PaymentController::class,'createPaymentIntent']);
        Route::post('payments/confirm', [PaymentController::class,'confirmPayment']); // optional server confirm

        // Customer cards
        Route::prefix('payments')->group(function () {
            Route::post('cards', [PaymentController::class,'addCard']);
            Route::get('cards', [PaymentController::class,'listCards']);
            Route::post('cards/{card}/default', [PaymentController::class,'makeDefault']);
            // TEST ONLY: Create test payment_method
            Route::post('test/create-payment-method', [PaymentController::class,'createTestPaymentMethod']);
        });

        // Notifications
        Route::controller(NotificationController::class)->prefix('notifications')->group(function () {
            Route::get('/', 'index');
            Route::get('/unread-count', 'unreadCount');
            Route::post('/{id}/read', 'markAsRead');
            Route::post('/mark-all-read', 'markAllAsRead');
            Route::delete('/{id}', 'destroy');
        });
        
    });

  // Webhooks (Stripe)
  Route::post('webhooks/stripe', [PaymentController::class,'webhook'])->name('webhooks.stripe');
});

