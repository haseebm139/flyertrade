<?php
namespace App\Services\Booking;

use App\Models\User;
use App\Models\Booking;
use App\Models\BookingSlot;
use App\Models\ProviderWorkingHour;
use App\Models\BookingReschedule;
use App\Models\ProviderService;
use App\Models\Review;

use Carbon\Carbon;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Auth;
use App\Services\Payment\StripeService;
use App\Services\Notification\NotificationService;
use Illuminate\Support\Facades\DB; 

use App\Models\Setting;

class BookingService
{

 
    public function __construct(
        private StripeService $stripe,
        private NotificationService $notificationService
    ) {}

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
                    'message' => "Provider is not available on {$slot['service_date']} between {$slot['start_time']} - {$slot['end_time']}."
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
            // Calculate service charges dynamically based on admin settings
            $percentage = (float) \App\Models\Setting::get('service_charge_percentage', 25); 
            $serviceCharges = ($data['total_price'] * $percentage) / 100;
              
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
                'total_price' => $data['total_price'] ,
                'service_charges' => $serviceCharges,
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

            $booking = $booking->load('slots','customer','provider','providerService.service', 'review');
            
            // Send notifications
            // notifyNewBookingCreated sends to admin only
            // notifyBookingCreated sends to provider, customer, and admin
            // So we only need notifyBookingCreated to avoid duplicate admin notifications
            $this->notificationService->notifyBookingCreated($booking);

            // Add review status
            $booking = $this->addReviewStatus($booking);

            return $booking;
        });
    }

    public function directCreate(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Check if provider offers the service
            $providerService = ProviderService::where('user_id', $data['provider_id'])
                ->where('service_id', $data['service_id'])
                ->first();

            // if (!$providerService) {
            //     return ['error' => true, 'message' => 'Provider does not offer this service.'];
            // }

            // 2. Calculate total minutes and check slot conflicts
            $totalMinutes = 0;
            foreach ($data['slots'] as $slot) {
                $start = Carbon::createFromFormat('H:i', $slot['start_time']);
                $end = Carbon::createFromFormat('H:i', $slot['end_time']);
                $duration = $start->diffInMinutes($end);

                if ($duration <= 0) {
                    return ['error' => true, 'message' => 'Invalid slot duration for ' . $slot['service_date']];
                }

                $totalMinutes += $duration;

                // Check for conflicts with other bookings
                // $hasConflict = DB::table('booking_slots as bs')
                //     ->join('bookings as b', 'b.id', '=', 'bs.booking_id')
                //     ->where("b.provider_id", $data['provider_id'])
                //     ->whereIn('b.status', ['awaiting_provider', 'confirmed', 'in_progress'])
                //     ->whereDate('bs.service_date', $slot['service_date'])
                //     ->where(function ($q) use ($slot) {
                //         $q->where('bs.start_time', '<', $slot['end_time'])
                //             ->where('bs.end_time', '>', $slot['start_time']);
                //     })
                //     ->exists();

                // if ($hasConflict) {
                //     return ['error' => true, 'message' => "Provider is already booked on {$slot['service_date']} between {$slot['start_time']} - {$slot['end_time']}."];
                // }
            }

            // 3. Calculate service charges (admin commission)
            $percentage = (float) Setting::get('service_charge_percentage', 25);
            $serviceCharges = ($data['total_price'] * $percentage) / 100;

            // 4. Create the booking with 'confirmed' status
            $booking = Booking::create([
                'booking_ref' => $this->makeRef(),
                'customer_id' => $data['customer_id'],
                'provider_id' => $data['provider_id'],
                'service_id' => $data['service_id'],
                'provider_service_id' => $providerService ? $providerService->id : null,
                'booking_address' => $data['booking_address'],
                'booking_description' => $data['booking_description'] ?? null,
                'status' => 'confirmed', // Automatically accepted
                'booking_type' => 'custom',
                'booking_working_minutes' => $totalMinutes,
                'total_price' => $data['total_price'],
                'service_charges' => $serviceCharges,
                'confirmed_at' => now(),
                'expires_at' => now()->addHours(2), // Standard expiry
            ]);

            // 5. Create slots
            foreach ($data['slots'] as $slot) {
                BookingSlot::create([
                    'booking_id' => $booking->id,
                    'service_date' => $slot['service_date'],
                    'start_time' => $slot['start_time'],
                    'end_time' => $slot['end_time'],
                    'duration_minutes' => Carbon::createFromFormat('H:i', $slot['start_time'])
                        ->diffInMinutes(Carbon::createFromFormat('H:i', $slot['end_time'])),
                ]);
            }

            $booking = $booking->load('slots', 'customer', 'provider', 'providerService.service');

            // 6. Notify both parties
            $this->notificationService->notifyBookingCreated($booking);
            $this->notificationService->notifyBookingConfirmed($booking);

            return ['error' => false, 'booking' => $booking];
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
        $booking = $booking->fresh('slots', 'provider', 'customer','providerService.service', 'review');
        
        // Send notification
        $this->notificationService->notifyBookingConfirmed($booking);
        
        // Add review status
        $booking = $this->addReviewStatus($booking);
        
        return $booking;
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
        $booking = $booking->fresh('slots', 'provider', 'customer','providerService.service');
        
        // Send notification
        $this->notificationService->notifyBookingRejected($booking);
        
        return $booking;
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
        $booking = $booking->fresh('slots', 'provider', 'customer','providerService.service');
        
        // Send notification
        $this->notificationService->notifyBookingStarted($booking);
        
        return $booking;
    }

    public function cancel(Booking $booking, string $cancelReason){
        // if ($booking->status !== 'in_progress') {
        //      return [
        //         'error' => true,
        //         'message' => 'Only in progress bookings can be cancelled.'
        //     ]; 
        // } 
        $cancelledBy = Auth::id() === $booking->customer_id ? 'customer' : 'provider';
        $booking->update(['status' => 'cancelled', 'cancelled_at' => now(), 'cancelled_reason' => $cancelReason]);
        $booking = $booking->load('slots', 'provider', 'customer','providerService.service');
        
        // Send notification
        $this->notificationService->notifyBookingCancelled($booking, $cancelledBy);
        
        return $booking;
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
        $booking = $booking->fresh('slots', 'provider', 'customer','providerService.service', 'review');
        
        // Send notifications
        $this->notificationService->notifyJobCompleted($booking);
        
        // Add review status
        $booking = $this->addReviewStatus($booking);
        
        return $booking;
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
            'reschedule_initiated_by' => $isCustomer ? 'customer' : 'provider',
            'reschedule_response' => null, // Reset response when new request is made
        ]);
        
        // Send notification
        $this->notificationService->notifyRescheduleRequested($booking, $reschedule, $isCustomer ? 'customer' : 'provider');
        
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
            
             
            $booking->update([
                'status' => 'confirmed',
                'reschedule_response' => 'accepted',
            ]);
            $reschedule->update(['status' => 'accepted']);
            
            // Send notification
            $this->notificationService->notifyRescheduleAccepted($booking, $reschedule);

        } elseif ($response === 'reject') {
            
            $reschedule->update(['status' => 'rejected']);
            
             
            $booking->update([
                'reschedule_response' => 'rejected',
            ]);
            
            // Send notification
            $this->notificationService->notifyRescheduleRejected($booking, $reschedule);

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
                    $this->notificationService->notifyBookingExpired($booking);
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
        return Booking::with('slots', 'provider', 'customer','providerService.service','latestPendingReschedule')->where('provider_id', $providerId)->where('status', 'confirmed')->paginate(10);
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
        $bookings = Booking::with('slots', 'provider', 'customer','providerService.service')
            ->where('customer_id', $customerId)
            ->where('status', 'confirmed')
            ->paginate(10);
        
        // Add late status for each booking
        foreach ($bookings as $booking) {
            $lateCheck = $this->isProviderLate($booking);
            $booking->setAttribute('is_provider_late', $lateCheck['is_late']);
            $booking->setAttribute('can_take_late_action', $lateCheck['can_take_action'] ?? false);
        }
        
        return $bookings;
    }

    public function completedBookingsCustomer($customerId)
    {
        $bookings = Booking::with('slots', 'provider', 'customer','providerService.service', 'review')
            ->where('customer_id', $customerId)
            ->where('status', 'completed')
            ->paginate(10);
        
        return $this->addReviewStatus($bookings);
    }

    public function cancelledBookingsCustomer($customerId)
    {
        return Booking::with('slots', 'provider', 'customer','providerService.service')
            ->where('customer_id', $customerId)
            ->whereIn('status', ['cancelled', 'rejected'])
            ->paginate(10);
    }

    public function processPayment($id): array
    {
        $booking = Booking::with('slots')->find($id);
        if (!$booking) {
            return ['error' => true, 'message' => 'Booking not found'];
        }
        $booking->paid_at = now();
        $booking->save();
        
        // Send notification (if transaction exists)
        // $transaction = \App\Models\Transaction::where('booking_id', $booking->id)
        //     ->where('status', 'succeeded')
        //     ->first();
        
        // if ($transaction) {
        //     $this->notificationService->notifyPaymentSuccess($transaction);
        //     $this->notificationService->notifyPaymentSuccessful($transaction);
        // }
        
        return ['error' => false, 'message' => 'Payment processed successfully.'];
    }
    public function onGoingBookingsCustomer($customerId)
    {
        return Booking::with('slots', 'provider', 'customer','providerService.service')->where('customer_id', $customerId)->where('status', 'in_progress')->paginate(10);
    }

    /**
     * Check if review has been given for a booking
     * 
     * @param int|Booking $booking
     * @return bool
     */
    public function isReviewGiven($booking): bool
    {
        if ($booking instanceof Booking) {
            return $booking->review()->exists();
        }
        
        return Review::where('booking_id', $booking)->exists();
    }

    /**
     * Add is_review_given status to booking(s)
     * 
     * @param Booking|\Illuminate\Database\Eloquent\Collection $bookings
     * @return Booking|\Illuminate\Database\Eloquent\Collection
     */
    public function addReviewStatus($bookings)
    {
        if ($bookings instanceof Booking) {
            $bookings->setAttribute('is_review_given', $this->isReviewGiven($bookings));
            return $bookings;
        }

        // For collections/paginated results
        $bookingIds = $bookings->pluck('id')->toArray();
        $reviewedBookingIds = Review::whereIn('booking_id', $bookingIds)
            ->pluck('booking_id')
            ->toArray();

        foreach ($bookings as $booking) {
            $booking->setAttribute('is_review_given', in_array($booking->id, $reviewedBookingIds));
        }

        return $bookings;
    }

    /**
     * Check if provider is late for an upcoming booking
     * 
     * @param Booking $booking
     * @param int $lateMinutesThreshold Minutes after start time to consider late (default: 15)
     * @return array
     */
    public function isProviderLate(Booking $booking, int $lateMinutesThreshold = 15): array
    {
        // Only check for confirmed/upcoming bookings
        if (!in_array($booking->status, ['confirmed'])) {
            return [
                'is_late' => false,
                'message' => 'Booking is not in upcoming status.'
            ];
        }

        // Get the first slot (earliest date/time)
        $firstSlot = $booking->slots()
            ->orderBy('service_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->first();

        if (!$firstSlot) {
            return [
                'is_late' => false,
                'message' => 'No slots found for this booking.'
            ];
        }

        // Combine date and time to create datetime
        $slotDateTime = Carbon::parse($firstSlot->service_date . ' ' . $firstSlot->start_time);
        $now = Carbon::now();

        // Check if slot time has passed
        if ($now->lt($slotDateTime)) {
            return [
                'is_late' => false,
                'message' => 'Booking time has not arrived yet.',
                'slot_datetime' => $slotDateTime->toDateTimeString(),
                'minutes_until_slot' => $now->diffInMinutes($slotDateTime, false)
            ];
        }

        // Check if provider is late (past start time + threshold)
        $lateThreshold = $slotDateTime->copy()->addMinutes($lateMinutesThreshold);
        $isLate = $now->gte($lateThreshold);
        $minutesLate = $isLate ? $now->diffInMinutes($slotDateTime, false) : 0;

        return [
            'is_late' => $isLate,
            'message' => $isLate ? 'Provider is running late.' : 'Provider is on time.',
            'slot_datetime' => $slotDateTime->toDateTimeString(),
            'minutes_late' => $minutesLate,
            'late_threshold_minutes' => $lateMinutesThreshold,
            'can_take_action' => $isLate && !$booking->late_action_taken
        ];
    }

    /**
     * Handle late action for a booking
     * 
     * @param Booking $booking
     * @param string $action 'wait', 'reschedule', or 'escalate'
     * @param array|null $newSlots Required if action is 'reschedule'
     * @return array
     */
    public function handleLateAction(Booking $booking, string $action, ?array $newSlots = null): array
    {
        // Validate booking status
        if ($booking->status !== 'confirmed') {
            return [
                'error' => true,
                'message' => 'Only confirmed/upcoming bookings can have late actions.'
            ];
        }

        // Check if action already taken
        if ($booking->late_action_taken) {
            return [
                'error' => true,
                'message' => 'Late action has already been taken for this booking.',
                'previous_action' => $booking->late_action_type
            ];
        }

        // Validate action type
        if (!in_array($action, ['wait', 'reschedule', 'escalate'])) {
            return [
                'error' => true,
                'message' => 'Invalid action. Must be: wait, reschedule, or escalate.'
            ];
        }

        // Check if provider is actually late
        $lateCheck = $this->isProviderLate($booking);
        if (!$lateCheck['is_late']) {
            return [
                'error' => true,
                'message' => 'Provider is not late. Action cannot be taken.',
                'late_check' => $lateCheck
            ];
        }

        // Handle different actions
        switch ($action) {
            case 'wait':
                // Customer chooses to wait - just mark action taken
                $booking->update([
                    'late_action_taken' => true,
                    'late_action_type' => 'wait',
                    'late_action_at' => now()
                ]);

                return [
                    'error' => false,
                    'message' => 'You have chosen to wait for the provider.',
                    'booking' => $booking->fresh(['slots', 'provider', 'customer', 'providerService.service'])
                ];

            case 'reschedule':
                // Customer wants to reschedule - use existing reschedule logic
                // if (!$newSlots || empty($newSlots)) {
                //     return [
                //         'error' => true,
                //         'message' => 'New slots are required for rescheduling.'
                //     ];
                // }

                // Use existing requestReschedule method
                $rescheduleResult = $this->requestReschedule($booking, $newSlots);
                
                // if ($rescheduleResult['error']) {
                //     return $rescheduleResult;
                // }

                // Mark late action
                $booking->update([
                    'late_action_taken' => true,
                    'late_action_type' => 'reschedule',
                    'late_action_at' => now()
                ]);

                return [
                    'error' => false,
                    'message' => 'Reschedule request sent due to provider being late.',
                    'booking' => $booking->fresh(['slots', 'provider', 'customer', 'providerService.service']),
                    // 'reschedule' => $rescheduleResult['reschedule']
                ];

            case 'escalate':
                // Escalate to admin - mark action and potentially notify admin
                $this->notificationService->notifyProviderLateEscalation($booking);
                $booking->update([
                    'late_action_taken' => true,
                    'late_action_type' => 'escalate',
                    'late_action_at' => now()
                ]);

                // Send notification to admin about escalation
                // $this->notificationService->notifyProviderLateEscalation($booking);

                return [
                    'error' => false,
                    'message' => 'Issue has been escalated. Admin will be notified.',
                    'booking' => $booking->fresh(['slots', 'provider', 'customer', 'providerService.service'])
                ];

            default:
                return [
                    'error' => true,
                    'message' => 'Invalid action type.'
                ];
        }
    }
}
