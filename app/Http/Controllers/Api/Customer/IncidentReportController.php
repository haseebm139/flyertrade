<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Customer\StoreIncidentRequest;
use App\Models\Dispute;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IncidentReportController extends Controller
{
    public function store(StoreIncidentRequest $request, $bookingId)
    {
        try {
            return DB::transaction(function () use ($request, $bookingId) {
                $userId = Auth::id();
                 
                // 1. Security Check: Verify Booking ownership and existence
                $booking = Booking::where('id', $bookingId)
                    ->where('customer_id', $userId)
                    ->first(); 
                if (!$booking) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Booking not found or you do not have permission to report an incident for this booking.'
                    ], 403);
                }

                // 2. Security Check: Ensure incident is not reported for cancelled bookings
                // if ($booking->status === 'cancelled') {
                //     return response()->json([
                //         'status' => 'error',
                //         'message' => 'You cannot report an incident for a cancelled booking.'
                //     ], 422);
                // }

                // 3. Security Check: Prevent duplicate reports for the same booking
                $existingDispute = Dispute::where('booking_id', $bookingId)
                    ->where('user_id', $userId)
                    ->first();

                if ($existingDispute) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'You have already reported an incident for this booking.'
                    ], 422);
                }

                $data = $request->validated();
                $data['user_id'] = $userId;
                $data['booking_id'] = $bookingId;
                $data['status'] = 'unresolved';
                if ($request->hasFile('attachment')) {
                    $path = $request->file('attachment')->store('customer/incidents', 'public');
                    $data['attachment'] = 'storage/' . $path;
                }

                $dispute = Dispute::create($data);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Incident reported successfully.',
                    'data' => $dispute
                ], 201);
            });
        } catch (\Exception $e) {
            Log::error('Incident reporting failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while reporting the incident. Please try again later.'
            ], 500);
        }
    }
}
