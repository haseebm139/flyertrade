<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookingController extends Controller
{


     protected $bookingRepo;

    public function __construct(BookingRepository $bookingRepo)
    {
        $this->bookingRepo = $bookingRepo;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'provider_id' => 'required|exists:users,id',
            'start_date'  => 'required|date|after_or_equal:today',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
        ]);

        try {
            $booking = $this->bookingRepo->createBooking($data);

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'data'    => $this->formatBooking($booking)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    private function formatBooking($booking)
    {
        $days = [];
        $period = \Carbon\CarbonPeriod::create($booking->start_date, $booking->end_date);

        foreach ($period as $date) {
            $days[] = [
                'date' => $date->toDateString(),
                'day_name' => $date->format('l'),
                'start_time' => $booking->start_time,
                'end_time'   => $booking->end_time,
            ];
        }

        return [
            'id' => $booking->id,
            'customer_id' => $booking->customer_id,
            'provider_id' => $booking->provider_id,
            'start_date' => $booking->start_date,
            'end_date'   => $booking->end_date,
            'status'     => $booking->status,
            'days'       => $days
        ];
    }
    public function createBooking($data)
    {
        $start = Carbon::parse($data['start_date']);
        $end   = Carbon::parse($data['end_date']);

        // loop over each day in range
        $period = CarbonPeriod::create($start, $end);

        foreach ($period as $date) {
            $dayName = strtolower($date->format('l')); // e.g. monday
            $workingHour = ProviderWorkingHour::where('user_id', $data['provider_id'])
                ->where('day', $dayName)
                ->where('is_active', true)
                ->first();

            if (!$workingHour) {
                throw new \Exception("Provider not available on {$dayName}");
            }

            // Check time conflict
            if (
                $data['start_time'] < $workingHour->start_time ||
                $data['end_time']   > $workingHour->end_time
            ) {
                throw new \Exception("Provider not available at given time on {$dayName}");
            }

            $conflict = Booking::where('provider_id', $data['provider_id'])
                ->whereDate('start_date', '<=', $date)
                ->whereDate('end_date', '>=', $date)
                ->where(function($q) use($data) {
                    $q->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                      ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']]);
                })
                ->exists();

            if ($conflict) {
                throw new \Exception("Provider already booked on {$date->toDateString()}");
            }
        }

        // If passed all checks, create booking
        return Booking::create($data);
    }

    public function createBooking($customerId, $providerId, $startDate, $endDate, $startTime, $endTime)
    {
        return DB::transaction(function () use ($customerId, $providerId, $startDate, $endDate, $startTime, $endTime) {

            $period = new \DatePeriod(
                Carbon::parse($startDate),
                \DateInterval::createFromDateString('1 day'),
                Carbon::parse($endDate)->addDay()
            );

            foreach ($period as $date) {
                $dayName = strtolower($date->format('l'));

                $workingHour = ProviderWorkingHour::where('user_id', $providerId)
                    ->where('day', $dayName)
                    ->where('is_active', true)
                    ->first();

                if (!$workingHour) {
                    throw new \Exception("Provider not available on {$dayName}");
                }

                if (
                    $startTime < $workingHour->start_time ||
                    $endTime > $workingHour->end_time
                ) {
                    throw new \Exception("Provider not available at selected time on {$dayName}");
                }

                $overlap = Booking::where('provider_id', $providerId)
                    ->whereDate('start_date', '<=', $date)
                    ->whereDate('end_date', '>=', $date)
                    ->where(function ($q) use ($startTime, $endTime) {
                        $q->whereBetween('start_time', [$startTime, $endTime])
                          ->orWhereBetween('end_time', [$startTime, $endTime]);
                    })
                    ->whereIn('status', ['pending', 'accepted', 'rescheduled'])
                    ->exists();

                if ($overlap) {
                    throw new \Exception("Provider already booked on {$date->toDateString()}");
                }
            }

            return Booking::create([
                'customer_id' => $customerId,
                'provider_id' => $providerId,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => 'pending',
            ]);
        });
    }

    public function updateStatus($bookingId, $status, $newDate = null, $newTime = null)
    {
        $booking = Booking::findOrFail($bookingId);

        if ($status === 'rescheduled' && $newDate && $newTime) {
            $booking->start_date = $newDate;
            $booking->start_time = $newTime;
        }

        $booking->status = $status;
        $booking->save();

        return $booking;
    }


    protected $repo;

    public function __construct(BookingRepository $repo)
    {
        $this->repo = $repo;
    }

    // Customer creates booking
    public function create(Request $request)
    {
        $data = $request->validate([
            'provider_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        try {
            $booking = $this->repo->createBooking(
                auth()->id(),
                $data['provider_id'],
                $data['start_date'],
                $data['end_date'],
                $data['start_time'],
                $data['end_time']
            );

            return response()->json(['success' => true, 'data' => $booking]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    // Provider accept booking
    public function accept($id)
    {
        $booking = $this->repo->updateStatus($id, 'accepted');
        return response()->json(['success' => true, 'data' => $booking]);
    }

    // Provider reject booking
    public function reject($id)
    {
        $booking = $this->repo->updateStatus($id, 'rejected');
        return response()->json(['success' => true, 'data' => $booking]);
    }

    // Provider reschedule booking
    public function reschedule(Request $request, $id)
    {
        $data = $request->validate([
            'new_date' => 'required|date',
            'new_time' => 'required',
        ]);

        $booking = $this->repo->updateStatus($id, 'rescheduled', $data['new_date'], $data['new_time']);
        return response()->json(['success' => true, 'data' => $booking]);
    }
}
