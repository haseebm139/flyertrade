<?php
namespace App\Services\Booking;

use App\Models\User;
use App\Models\Booking;
use App\Models\BookingSlot;
use App\Models\ProviderWorkingHour;
use Carbon\Carbon;
use Illuminate\Support\Str;

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
        if (
            $slot['start_time'] < $workingHour->start_time ||
            $slot['end_time'] > $workingHour->end_time
        ) {
            
            return 'not_available';
        }

        // 3. Check if provider is fully booked
        if ($this->providerHasConflict($providerId, $slot['service_date'], $slot['start_time'], $slot['end_time']
        )) {
            return 'fully_booked';
        }  

        return 'available';
    }

    public function create(array $data) 
    {
         
        $totalMinutes = 0;

        // $providerIsAvailable = $this->providerIsAvailable(
        //     $data['provider_id']
        // );
        // if ($providerIsAvailable == false) {
        //     return [
        //         'error' => true,
        //         'message' => 'Provider is not available.'
        //     ];
        // }   



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
         
        $intent = $this->stripe->createAndConfirmIntent(
            amountCents: $amountCents,
            currency: 'usd',
            paymentMethodId: $data['payment_method_id'],
            metadata: [
                'customer_id' => (string)auth()->user()->id,
                'provider_id' => (string)$data['provider_id'],
            ]
        );
        
        // Payment might be requires_action in rare cases; handle by client if needed
        if (!in_array($intent->status, ['succeeded', 'requires_action'])) {
            return [
                'error' => true,
                'message' => 'Payment could not be confirmed'
            ];  
        }
         
        // Persist booking + slots
        return DB::transaction(function () use ($data, $totalMinutes, $intent) {
            $booking = Booking::create([
                'booking_ref' => $this->makeRef(),
                'customer_id' => auth()->user()->id,
                'provider_id' => $data['provider_id'],
                'booking_address' => $data['booking_address'],
                'booking_description' => $data['booking_description'] ?? null,
                'status' => 'awaiting_provider',
                'booking_working_minutes' => $totalMinutes,
                'total_price' => $data['total_price'],
                'service_charges' => $data['service_charges'] ?? 0,
                'stripe_payment_intent_id' => $intent->id,
                'stripe_payment_method_id' => $data['payment_method_id'],
                'paid_at' => $intent->status === 'succeeded' ? now() : null,
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

            return $booking->load('slots');
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
        return $booking->fresh('slots');
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
        return $booking->fresh('slots');
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
        return $booking->fresh('slots');
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
        return $booking->fresh('slots');
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
}
