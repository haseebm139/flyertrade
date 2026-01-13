<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Transaction;
use App\Services\Notification\NotificationService;
use Stripe\Webhook;
use Illuminate\Support\Facades\DB;

class StripeWebhookController extends Controller
{
    public function __construct(private NotificationService $notificationService) {}

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
                    $booking = Booking::where('stripe_payment_intent_id', $pi->id)
                        ->whereNull('paid_at')
                        ->first();
                    
                    if ($booking) {
                        $booking->update(['paid_at' => now()]);
                    }
                    
                    // Update transaction
                    $transaction = Transaction::where('stripe_payment_intent_id', $pi->id)
                        ->where('status', '!=', 'succeeded')
                        ->first();
                    
                    if ($transaction) {
                        $transaction->update([
                            'status' => 'succeeded',
                            'completed_at' => now(),
                            'stripe_charge_id' => $pi->charges->data[0]->id ?? null,
                        ]);
                        
                        // Send notifications
                        $this->notificationService->notifyPaymentSuccess($transaction);
                        $this->notificationService->notifyPaymentSuccessful($transaction);
                    }
                });
                break;

            case 'payment_intent.payment_failed':
                $pi = $event->data->object;
                DB::transaction(function () use ($pi) {
                    $transaction = Transaction::where('stripe_payment_intent_id', $pi->id)
                        ->where('status', '!=', 'failed')
                        ->first();
                    
                    if ($transaction) {
                        $transaction->update([
                            'status' => 'failed',
                            'failed_at' => now(),
                            'failure_reason' => $pi->last_payment_error->message ?? 'Payment failed',
                        ]);
                        
                        // Send notification
                        $this->notificationService->notifyPaymentFailed($transaction);
                    }
                });
                break;

            case 'charge.refunded':
                $charge = $event->data->object;
                if (!empty($charge->payment_intent)) {
                    DB::transaction(function () use ($charge) {
                        $booking = Booking::where('stripe_payment_intent_id', $charge->payment_intent)
                            ->first();
                        
                        if ($booking) {
                            $booking->update(['status' => 'refunded']);
                        }
                        
                        $transaction = Transaction::where('stripe_payment_intent_id', $charge->payment_intent)
                            ->first();
                        
                        if ($transaction) {
                            $transaction->update(['status' => 'refunded']);
                            
                            // Create refund transaction
                            $refundTransaction = Transaction::create([
                                'booking_id' => $transaction->booking_id,
                                'customer_id' => $transaction->customer_id,
                                'provider_id' => $transaction->provider_id,
                                'transaction_ref' => Transaction::generateRef(),
                                'type' => 'refund',
                                'status' => 'succeeded',
                                'amount' => $charge->amount_refunded / 100,
                                'service_charges' => 0,
                                'net_amount' => $charge->amount_refunded / 100,
                                'currency' => $charge->currency,
                                'stripe_charge_id' => $charge->id,
                                'completed_at' => now(),
                            ]);
                            
                            // Send notification
                            $this->notificationService->notifyRefundProcessed($refundTransaction);
                        }
                    });
                }
                break;

            // add more events if needed
        }

        return response()->json(['status' => 'ok']);
    }
}
