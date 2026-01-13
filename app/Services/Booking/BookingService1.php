<?php
namespace App\Services\Booking;

use App\Models\User;
use App\Models\Booking;
use App\Models\BookingSlot;
use App\Models\ProviderWorkingHour;
use App\Models\BookingReschedule;
use App\Models\ProviderService;
use App\Models\Transaction;

use Carbon\Carbon;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Auth;
use App\Services\Payment\StripeService;
use Illuminate\Support\Facades\DB; 

class BookingService
{
    public function __construct(private StripeService $stripe) {}

    public function checkAvailability(array $slot, int $providerId): string
    {
        $dayName = strtolower(Carbon::parse($slot['service_date'])->format('l'));
         
        // 1. Get provider working hours
        $workingHour = ProviderWorkingHour::where('user_id', $providerId)
            ->where('day', $dayName)
            ->first();
            
            
         
        if (!$workingHour || !$workingHour->is_active) {
            return 'not_available';
        }       
        // 2. Validate slot inside working hours
        // if (
        //     $slot['start_time'] < $workingHour->start_time ||
        //     $slot['end_time'] > $workingHour->end_time
        //     ) {
                
                 
        //         return 'not_available';
        //     }

        // 3. Check if provider is fully booked
        if ($this->providerHasConflict($providerId, $slot['service_date'], $slot['start_time'], $slot['end_time']
        )) {
            return 'fully_booked';
        }  

        return 'available';
    }

    public function providerHasService($providerId, $serviceId) 
    {
         
        $providerHasService = ProviderService::where('user_id', $providerId)
            ->where('service_id', $serviceId)
            ->first() ;
        return ['status'=>$providerHasService ? true : false, 'data'=>$providerHasService];
        
        // return $providerHasService ? true : false;
            
    }
    public function create(array $data) 
    {
         
        $totalMinutes = 0; 
        $providerIsAvailable = $this->providerIsAvailable(
            $data['provider_id']
        );

         
        $providerHasService = $this->providerHasService($data['provider_id'], $data['service_id']);
        if (!$providerHasService['status']) {
            return [
                'error' => true,
                'message' => 'Provider does not offer this service.'
            ];
            
        } 
        if ($providerIsAvailable == false) {
            return [
                'error' => true,
                'message' => 'Provider is not available.'
            ];
        }   



        foreach ($data['slots'] as $slot) {
            $status = $this->checkAvailability($slot, $data['provider_id']);
             
            if ($status !== 'available') {
                return [
                    'error' => true,
                    'message' => "Provider already booked on {$slot['service_date']} between {$slot['start_time']} - {$slot['end_time']}."
                ]; 
            }  
             
            $duration = $this->minutesBetween($slot['start_time'], $slot['end_time']);
             
            if ($duration <= 0) {
                return [
                    'error' => true,
                    'message' => 'Invalid slot duration.'
                ];   
            }
            
            $totalMinutes += $duration;
             
             
             
        }         
        // Stripe charge (in cents)
        $amountCents = (int) round($data['total_price'] * 100);
         
        // $intent = $this->stripe->createAndConfirmIntent(
        //     amountCents: $amountCents,
        //     currency: 'usd',
        //     paymentMethodId: $data['payment_method_id'],
        //     metadata: [
        //         'customer_id' => (string)auth()->user()->id,
        //         'provider_id' => (string)$data['provider_id'],
        //     ]
        // );
        
        // Payment might be requires_action in rare cases; handle by client if needed
        // if (!in_array($intent->status, ['succeeded', 'requires_action'])) {
        //     return [
        //         'error' => true,
        //         'message' => 'Payment could not be confirmed'
        //     ];  
        // }
          
        // Persist booking + slots
        return DB::transaction(function () use ($data, $totalMinutes,$providerHasService) {
              
            $booking = Booking::create([
                'booking_ref' => $this->makeRef(),
                'customer_id' => auth()->user()->id,
                'provider_id' => $data['provider_id'],
                'service_id' => $data['service_id'],
                'provider_service_id' => $providerHasService['data']->id ,
                'booking_address' => $data['booking_address'],
                'booking_description' => $data['booking_description'] ?? null,
                'status' => 'awaiting_provider',
                'booking_working_minutes' => $totalMinutes,
                'total_price' => $data['total_price'],
                'service_charges' => $data['service_charges'] ?? 0,
                // 'stripe_payment_intent_id' => $intent->id,
                // 'stripe_payment_method_id' => $data['payment_method_id'],
                // 'paid_at' => $intent->status === 'succeeded' ? now() : null,
                // 'paid_at' => null,
                'expires_at' => now()->addHours(2),
            ]);

            foreach ($data['slots'] as $slot) {
                BookingSlot::create([
                    'booking_id' => $booking->id,
                    'service_date' => $slot['service_date'],
                    'start_time' => $slot['start_time'],
                    'end_time' => $slot['end_time'],
                    'duration_minutes' => $this->minutesBetween($slot['start_time'], $slot['end_time']),
                ]);
            }

            return $booking->load('slots','customer','provider','providerService.service');
        });
    }

    public function accept(Booking $booking) 
    {
        if ($booking->status !== 'awaiting_provider') {
            return [
                'error' => true,
                'message' => 'Booking not awaiting provider.'
            ];  
        }
        $booking->update(['status' => 'confirmed', 'confirmed_at' => now()]);
        return $booking->fresh('slots', 'provider', 'customer','providerService.service');
    }

    public function reject(Booking $booking) 
    {
        if ($booking->status !== 'awaiting_provider') {
            return [
                'error' => true,
                'message' => 'Booking not awaiting provider.'
            ];
             
        }
        
        if ($booking->stripe_payment_intent_id) {
            $this->stripe->refundByPaymentIntent($booking->stripe_payment_intent_id);
        }

        $booking->update(['status' => 'rejected','rejected_at' => now()]);
        return $booking->fresh('slots', 'provider', 'customer','providerService.service');
    }
    public function start(Booking $booking) 
    {
        if ($booking->status !== 'confirmed') {
             return [
                'error' => true,
                'message' => 'Only confirmed bookings can be started.'
            ]; 
        } 
        
        $booking->update(['status' => 'in_progress', 'started_at' => now()]); 
        return $booking->fresh('slots', 'provider', 'customer','providerService.service');
    }

    public function cancel(Booking $booking, string $cancelReason){
        // if ($booking->status !== 'in_progress') {
        //      return [
        //         'error' => true,
        //         'message' => 'Only in progress bookings can be cancelled.'
        //     ]; 
        // } 
        $booking->update(['status' => 'cancelled', 'cancelled_at' => now(), 'cancelled_reason' => $cancelReason]);
        return $booking->load('slots', 'provider', 'customer','providerService.service');
    }
    public function complete(Booking $booking) 
    {
        if ($booking->status !== 'in_progress') {
             return [
                'error' => true,
                'message' => 'Only in progress bookings can be completed.'
            ]; 
        }
        $booking->update(['status' => 'completed', 'completed_at' => now()]);
        return $booking->fresh('slots', 'provider', 'customer','providerService.service');
    }

    public function requestReschedule(Booking $booking, array $newSlots): array
    {
         
        if ($booking->status !== 'confirmed') {
             
            return [
                'error' => true,
                'message' => 'Only confirmed bookings can be rescheduled.'
            ];  
        }
        
        if (BookingReschedule::where('booking_id', $booking->id)->where('status', 'pending')->exists()) {
            return [
                'error' => true,
                'message' => 'A reschedule request is already pending.'
            ]; 
        }

        $reschedule = BookingReschedule::create([
            'booking_id'   => $booking->id,
            'requested_by' => Auth::id(),
            'old_slots'    => $booking->slots->toArray(),
            'new_slots'    => $newSlots,            
            'status'       => 'pending',
        ]);

        $isCustomer = Auth::id() === $booking->customer_id;
        $booking->update([
            'status' => $isCustomer ? 'reschedule_pending_provider' : 'reschedule_pending_customer',
        ]);
        
        return [
            'error' => false, 
            'reschedule' => $reschedule,
            'booking'    => $booking
        ];
    }

    public function respondReschedule(Booking $booking, string $response): array
    {
        $totalMinutes = 0;
        $reschedule = BookingReschedule::where('booking_id', $booking->id)
            ->where('status', 'pending')
            ->latest()
            ->first();
         
        if (!$reschedule) {
            return [
                'error' => true,
                'message' => 'No pending reschedule request found.'
            ] ;
        }
        if ($response === 'accept') {
            foreach ($reschedule->new_slots as $slot) {
                 
                $status = $this->checkAvailability($slot, $booking['provider_id']);
                 
                if ($status !== 'available') {
                    return [
                        'error' => true,
                        'message' => "Provider already booked on {$slot['service_date']} between {$slot['start_time']} - {$slot['end_time']}."
                    ]; 
                }  
                 
                $duration = $this->minutesBetween($slot['start_time'], $slot['end_time']);
                  
                if ($duration <= 0) {
                    return [
                        'error' => true,
                        'message' => 'Invalid slot duration.'
                    ];   
                }
                
                $totalMinutes += $duration;
                 
                 
                 
            }         
              
            $booking->slots()->delete();
            foreach ($reschedule->new_slots as $slot) {
                $duration = $this->minutesBetween($slot['start_time'], $slot['end_time']);
                $slot['duration_minutes'] = $duration;
                $booking->slots()->create($slot);
            }
            
             
            $booking->update(['status' => 'confirmed']);
            $reschedule->update(['status' => 'accepted']);

        } elseif ($response === 'reject') {
            $booking->update(['status' => 'confirmed']);
            $reschedule->update(['status' => 'rejected']);

        } else {
            return [
                'error' => true,
                'message' => 'Invalid response option.'
            ]; 
        }

        return [
            'error' => false, 
            'reschedule' => $reschedule,
            'booking'    => $booking
        ];
    }
    public function autoRejectExpired(): int
    {
        $query = Booking::query()
            ->where('status', 'awaiting_provider')
            ->where('expires_at', '<', now());

        $count = 0;
        $query->chunkById(100, function ($bookings) use (&$count) {
            foreach ($bookings as $booking) {
                try {
                    $this->reject($booking);
                    $count++;
                } catch (\Throwable) {}
            }
        });

        return $count;
    }

    private function minutesBetween(string $start, string $end): int
    {
         
        $s = Carbon::createFromFormat('H:i', $start);
        $e = Carbon::createFromFormat('H:i', $end);

         
        return $s->diffInMinutes($e);
    }

    private function providerHasConflict(int $providerId, string $date, string $start, string $end,): bool
    {
        // Conflict if any existing booking (awaiting_provider or confirmed) overlaps
        return \DB::table('booking_slots as bs')
            ->join('bookings as b', 'b.id', '=', 'bs.booking_id')
            ->where("b.provider_id", $providerId)
            ->whereIn('b.status', ['awaiting_provider','confirmed'])
            ->whereDate('bs.service_date', $date)
            ->where(function ($q) use ($start, $end) {
                $q->where('bs.start_time', '<', $end)
                ->where('bs.end_time', '>', $start);
            })
        ->exists();
    }

    private function makeRef(): string
    {
        return 'FT-'.now()->format('Ymd').'-'.strtoupper(Str::random(6));
    }

    public function providerIsAvailable($providerId)
    {
        $provider = User::find($providerId); 
        if (!$provider || $provider->providerProfile->availability_status != 'available') {
            return false; 
        }
        return true;
    }
    public function job($providerId){
        $data['pending'] = $this->pendingBookingsProvider($providerId);
        $data['ongoing'] = $this->onGoingBookingsProvider($providerId);
        $data['upcoming'] = $this->upcomingBookingsProvider($providerId);
        $data['completed'] = $this->completedBookingsProvider($providerId);
        $data['totalAmount'] = $this->totalAmountProvider($providerId);
        return $data;
    }
    public function pendingBookingsProvider($providerId)
    {
        return Booking::with('slots', 'provider', 'customer','providerService.service')->where('provider_id', $providerId)->where('status', 'awaiting_provider')->paginate(10);
    }

    public function onGoingBookingsProvider($providerId)
    {
        return Booking::with('slots', 'provider', 'customer','providerService.service')->where('provider_id', $providerId)->where('status', 'in_progress')->first();
    }

    public function upcomingBookingsProvider($providerId)
    {
        return Booking::with('slots', 'provider', 'customer','providerService.service')->where('provider_id', $providerId)->where('status', 'confirmed')->paginate(10);
    }

    public function completedBookingsProvider($providerId)
    {
        return Booking::with('slots', 'provider', 'customer','providerService.service')->where('provider_id', $providerId)->where('status', 'completed')->paginate(10);
    }

    public function totalAmountProvider($providerId)
    {
         
        return Booking::where('provider_id', $providerId)->where('status', 'completed')->sum('total_price');
    }

    public function pendingBookingsCustomer($customerId)
    {
        return Booking::with('slots', 'provider', 'customer','providerService.service')->where('customer_id', $customerId)->where('status', 'awaiting_provider')->paginate(10);
    }
    public function upcomingBookingsCustomer($customerId)
    {
        return Booking::with('slots', 'provider', 'customer','providerService.service')->where('customer_id', $customerId)->where('status', 'confirmed')->paginate(10);
    }

    public function completedBookingsCustomer($customerId)
    {
        return Booking::with('slots', 'provider', 'customer','providerService.service')->where('customer_id', $customerId)->where('status', 'completed')->paginate(10);
    }

    public function cancelledBookingsCustomer($customerId)
    {
        return Booking::with('slots', 'provider', 'customer','providerService.service')->where('customer_id', $customerId)->where('status', 'cancelled')->paginate(10);
    }

    /**
     * Process payment for a booking using Stripe
     * 
     * @param Booking $booking
     * @param string $paymentMethodId
     * @return array
     */
    public function processPayment(Booking $booking, string $paymentMethodId): array
    {
        // Validate booking status
        if ($booking->paid_at) {
            return [
                'error' => true, 
                'message' => 'Booking is already paid',
                'code' => 'ALREADY_PAID'
            ];
        }

        // Check if booking can accept payment (not cancelled/rejected)
        $invalidStatuses = ['cancelled', 'rejected', 'refunded'];
        if (in_array($booking->status, $invalidStatuses)) {
            return [
                'error' => true,
                'message' => "Cannot process payment for booking with status: {$booking->status}",
                'code' => 'INVALID_STATUS'
            ];
        }

        // Authorization check
        if ($booking->customer_id !== auth()->id()) {
            return [
                'error' => true,
                'message' => 'Unauthorized access to booking',
                'code' => 'UNAUTHORIZED'
            ];
        }

        // Calculate payment amount (including service charges)
        // Customer pays total_price which includes service charges
        $paymentAmount = $booking->total_price??0 + $booking->service_charges??0;
        $amountCents = (int) round($paymentAmount * 100);
        
        if ($amountCents <= 0) {
            return [
                'error' => true,
                'message' => 'Invalid booking amount',
                'code' => 'INVALID_AMOUNT'
            ];
        }

        // Get currency from booking or default to USD
        $currency = $booking->currency ?? 'usd';

        // Get or create Stripe customer
        $customer = $booking->customer;
        $stripeCustomerId = $this->stripe->ensureCustomer($customer);

        return DB::transaction(function () use ($booking, $paymentMethodId, $amountCents, $currency, $stripeCustomerId, $paymentAmount) {
            try {
                // Create and confirm payment intent with Stripe
                $intent = $this->stripe->createAndConfirmIntent(
                    amountCents: $amountCents,
                    currency: $currency,
                    paymentMethodId: $paymentMethodId,
                    customerId: $stripeCustomerId,
                    metadata: [
                        'booking_id' => (string) $booking->id,
                        'booking_ref' => $booking->booking_ref,
                        'customer_id' => (string) $booking->customer_id,
                        'provider_id' => (string) $booking->provider_id,
                        'total_price' => (string) $booking->total_price,
                        'service_charges' => (string) ($booking->service_charges ?? 0),
                        'payment_amount' => (string) $paymentAmount,
                    ]
                );

                // Handle different payment statuses
                $updateData = [
                    'stripe_payment_intent_id' => $intent->id,
                    'stripe_payment_method_id' => $paymentMethodId,
                ];

                // Calculate net amount (amount after service charges - provider receives this)
                $netAmount = $paymentAmount - ($booking->service_charges ?? 0);

                // Determine transaction status based on payment intent status
                $transactionStatus = match($intent->status) {
                    'succeeded' => 'succeeded',
                    'requires_action' => 'processing',
                    'requires_payment_method' => 'failed',
                    'canceled' => 'cancelled',
                    default => 'failed'
                };

                // Create transaction record
                $transaction = Transaction::create([
                    'booking_id' => $booking->id,
                    'customer_id' => $booking->customer_id,
                    'provider_id' => $booking->provider_id,
                    'transaction_ref' => Transaction::generateRef(),
                    'type' => 'payment',
                    'status' => $transactionStatus,
                    'amount' => $paymentAmount, // Total payment amount (including service charges)
                    'service_charges' => $booking->service_charges ?? 0, // Service charges (platform fee)
                    'net_amount' => $netAmount, // Net amount to provider (amount - service_charges)
                    'currency' => $currency,
                    'stripe_payment_intent_id' => $intent->id,
                    'stripe_payment_method_id' => $paymentMethodId,
                    'stripe_customer_id' => $stripeCustomerId,
                    'processed_at' => now(),
                    'metadata' => [
                        'booking_ref' => $booking->booking_ref,
                        'payment_intent_status' => $intent->status,
                        'client_secret' => $intent->client_secret ?? null,
                        'total_price' => (string) $booking->total_price,
                        'service_charges' => (string) ($booking->service_charges ?? 0),
                        'payment_amount' => (string) $paymentAmount,
                    ],
                ]);

                if ($intent->status === 'succeeded') {
                    $updateData['paid_at'] = now();
                    $transaction->update([
                        'status' => 'succeeded',
                        'completed_at' => now(),
                    ]);
                    $message = 'Payment processed successfully.';
                } elseif ($intent->status === 'requires_action') {
                    // 3D Secure or other authentication required
                    $message = 'Payment requires additional authentication.';
                } else {
                    // Payment failed or requires payment method
                    $transaction->update([
                        'status' => 'failed',
                        'failed_at' => now(),
                        'failure_reason' => "Payment intent status: {$intent->status}",
                    ]);
                    
                    return [
                        'error' => true,
                        'message' => "Payment could not be confirmed. Status: {$intent->status}",
                        'code' => 'PAYMENT_FAILED',
                        'payment_intent_id' => $intent->id,
                        'payment_status' => $intent->status,
                        'client_secret' => $intent->client_secret ?? null,
                        'transaction_id' => $transaction->id,
                    ];
                }

                // Update booking with payment information
                $booking->update($updateData);

                return [
                    'error' => false,
                    'message' => $message,
                    'payment_intent_id' => $intent->id,
                    'payment_status' => $intent->status,
                    'client_secret' => $intent->client_secret ?? null,
                    'requires_action' => $intent->status === 'requires_action',
                    'transaction_id' => $transaction->id,
                    'transaction_ref' => $transaction->transaction_ref,
                    'booking' => $booking->fresh(['slots', 'customer', 'provider', 'providerService.service'])
                ];

            } catch (\Stripe\Exception\CardException $e) {
                // Card was declined
                return [
                    'error' => true,
                    'message' => 'Card was declined: ' . $e->getDeclineCode(),
                    'code' => 'CARD_DECLINED',
                    'stripe_error' => $e->getMessage()
                ];
            } catch (\Stripe\Exception\RateLimitException $e) {
                // Too many requests
                return [
                    'error' => true,
                    'message' => 'Too many requests. Please try again later.',
                    'code' => 'RATE_LIMIT'
                ];
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                // Invalid parameters
                return [
                    'error' => true,
                    'message' => 'Invalid payment request: ' . $e->getMessage(),
                    'code' => 'INVALID_REQUEST'
                ];
            } catch (\Stripe\Exception\AuthenticationException $e) {
                // Authentication failed
                \Log::error('Stripe authentication failed', ['error' => $e->getMessage()]);
                return [
                    'error' => true,
                    'message' => 'Payment service error. Please contact support.',
                    'code' => 'SERVICE_ERROR'
                ];
            } catch (\Exception $e) {
                // Log unexpected errors
                \Log::error('Payment processing failed', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return [
                    'error' => true,
                    'message' => 'Payment processing failed. Please try again.',
                    'code' => 'PAYMENT_ERROR'
                ];
            }
        });
    }
    public function onGoingBookingsCustomer($customerId)
    {
        return Booking::with('slots', 'provider', 'customer','providerService.service')->where('customer_id', $customerId)->where('status', 'in_progress')->paginate(10);
    }
}
