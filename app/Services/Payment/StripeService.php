<?php
namespace App\Services\Payment;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Refund; 
use Stripe\PaymentMethod;
use Stripe\Customer;
use Stripe\Exception\InvalidRequestException;
use App\Models\User;

use Illuminate\Support\Facades\Log;
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

    /**
     * Ensure a Stripe customer exists for the given user and return the id.
     */
    public function ensureCustomer(User $user): string
    {
        if (!empty($user->stripe_customer_id)) {
            try {
                Customer::retrieve($user->stripe_customer_id);
                return $user->stripe_customer_id;
            } catch (InvalidRequestException $e) {
                Log::warning('Stripe customer missing: '.$e->getMessage());
                $user->forceFill(['stripe_customer_id' => null])->save();
            }
        }

        $customer = Customer::create([
            'email' => $user->email,
            'name'  => $user->name,
        ]);

        $user->update(['stripe_customer_id' => $customer->id]);

        return $customer->id;
    }

    /**
     * Attach a PaymentMethod to customer and optionally set it default.
     */
    public function attachPaymentMethod(string $customerId, string $paymentMethodId, bool $makeDefault = false): PaymentMethod
    {
        $paymentMethod = PaymentMethod::retrieve($paymentMethodId);

        $paymentMethod->attach(['customer' => $customerId]);

        if ($makeDefault) {
            Customer::update($customerId, [
                'invoice_settings' => [
                    'default_payment_method' => $paymentMethodId,
                ],
            ]);
        }

        return $paymentMethod;
    }
}

