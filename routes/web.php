<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Stripe\Stripe;
use Stripe\PaymentMethod;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-payment-method', function () {
    Stripe::setApiKey(config('services.stripe.secret'));

    $paymentMethod = PaymentMethod::create([
        'type' => 'card',
        'card' => [
            'token' => 'tok_visa', // Stripe built-in test token
        ],
    ]);

    return response()->json([
        'payment_method_id' => $paymentMethod->id,
    ]);
});
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
