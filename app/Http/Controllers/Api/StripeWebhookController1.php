<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Transaction;
use Stripe\Webhook;
use Illuminate\Support\Facades\DB;
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
                
                DB::transaction(function () use ($pi) {
                    // Update booking
                    Booking::where('stripe_payment_intent_id', $pi->id)
                        ->whereNull('paid_at')
                        ->update(['paid_at' => now()]);
                    
                    // Update transaction
                    Transaction::where('stripe_payment_intent_id', $pi->id)
                        ->where('status', '!=', 'succeeded')
                        ->update([
                            'status' => 'succeeded',
                            'completed_at' => now(),
                            'stripe_charge_id' => $pi->charges->data[0]->id ?? null,
                        ]);
                });
                break;

            case 'payment_intent.payment_failed':
                $pi = $event->data->object;
                
                DB::transaction(function () use ($pi) {
                    // Update transaction
                    Transaction::where('stripe_payment_intent_id', $pi->id)
                        ->where('status', '!=', 'failed')
                        ->update([
                            'status' => 'failed',
                            'failed_at' => now(),
                            'failure_reason' => $pi->last_payment_error->message ?? 'Payment failed',
                        ]);
                });
                break;

            case 'charge.refunded':
                $charge = $event->data->object;
                
                if (!empty($charge->payment_intent)) {
                    DB::transaction(function () use ($charge) {
                        // Update booking
                        Booking::where('stripe_payment_intent_id', $charge->payment_intent)
                            ->update(['status' => 'refunded']);
                        
                        // Create refund transaction or update existing
                        $originalTransaction = Transaction::where('stripe_payment_intent_id', $charge->payment_intent)
                            ->where('type', 'payment')
                            ->first();
                        
                        if ($originalTransaction) {
                            // Update original transaction
                            $originalTransaction->update([
                                'status' => 'refunded',
                            ]);
                            
                            // Create refund transaction record
                            Transaction::create([
                                'booking_id' => $originalTransaction->booking_id,
                                'customer_id' => $originalTransaction->customer_id,
                                'provider_id' => $originalTransaction->provider_id,
                                'transaction_ref' => Transaction::generateRef(),
                                'type' => 'refund',
                                'status' => 'succeeded',
                                'amount' => abs($charge->amount_refunded / 100), // Convert from cents
                                'service_charges' => 0,
                                'net_amount' => abs($charge->amount_refunded / 100),
                                'currency' => strtolower($charge->currency),
                                'stripe_payment_intent_id' => $charge->payment_intent,
                                'stripe_charge_id' => $charge->id,
                                'processed_at' => now(),
                                'completed_at' => now(),
                                'metadata' => [
                                    'refund_reason' => $charge->refund_reason ?? null,
                                    'original_transaction_id' => $originalTransaction->id,
                                ],
                            ]);
                        }
                    });
                }
                break;

            // add more events if needed
        }

        return response()->json(['status' => 'ok']);
    }
}
