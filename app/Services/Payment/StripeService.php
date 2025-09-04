<?php
namespace App\Services\Payment;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Refund; 
use Stripe\PaymentMethod;
use Stripe\Customer;

use Illuminate\Http\JsonResponse;
class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function testStripePayment(): PaymentIntent
    {
        $paymentMethod = \Stripe\PaymentMethod::create([
            'type' => 'card',
            'card' => ['token' => 'tok_visa'],
        ]); 
        // 2️⃣ Create + Confirm PaymentIntent
        return PaymentIntent::create([
            'amount' => 5000, // $50
            'currency' => 'usd',
            'payment_method' => $paymentMethod->id, // pm_xxx from tok_visa
            'confirmation_method' => 'automatic',
            'confirm' => true,             
            'payment_method_types' => ['card'], // ✅ required when disabling auto
             
        ]);

         
    } 
    public function createAndConfirmIntent(int $amountCents, string $currency , string $paymentMethodId, array $metadata = []): PaymentIntent
    { 
        $paymentMethod = PaymentMethod::create([
            'type' => 'card',
            'card' => ['token' => 'tok_visa'],
        ]); 
         
        return PaymentIntent::create([
            'amount' => $amountCents,
            'currency' => $currency,
            'payment_method' => $paymentMethod->id, 
            'confirmation_method' => 'automatic',
            'confirm' => true,             
            'payment_method_types' => ['card'],  
            'metadata' => $metadata,
        ]); 
       
         
        

    }

    public function refundByPaymentIntent(string $paymentIntentId): \Stripe\Refund
    {
        return Refund::create(['payment_intent' => $paymentIntentId]);
    }
}

