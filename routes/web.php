<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Stripe\Stripe;
use Stripe\PaymentMethod;
Route::get('/', function () {
    return view('welcome');
});
Route::view('/counter', 'counter');

Route::post('/counter/increment', function () {
    $count = cache()->increment('counter_value', 1);
    if ($count === false) {
        cache()->put('counter_value', 1);
        $count = 1;
    }
    event(new CounterUpdated($count));
    return response()->json(['count' => $count]);
});

Route::post('/counter/reset', function () {
    cache()->put('counter_value', 0);
    event(new CounterUpdated(0));
    return response()->json(['count' => 0]);
});

Route::get('/counter/current', function () {
    return response()->json(['count' => cache()->get('counter_value', 0)]);
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
