<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Stripe\Webhook;
class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        switch ($event->type) {
            case 'payment_intent.succeeded':
                $pi = $event->data->object;
                Booking::where('stripe_payment_intent_id', $pi->id)
                    ->whereNull('paid_at')
                    ->update(['paid_at' => now()]);
                break;

            case 'charge.refunded':
                $charge = $event->data->object;
                if (!empty($charge->payment_intent)) {
                    Booking::where('stripe_payment_intent_id', $charge->payment_intent)
                        ->update(['status' => 'refunded']);
                }
                break;

            // add more events if needed
        }

        return response()->json(['status' => 'ok']);
    }
}
