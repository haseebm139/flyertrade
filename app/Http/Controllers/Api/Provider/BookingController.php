<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Services\Booking\BookingService;
use App\Services\Notification\NotificationService;
use Illuminate\Http\JsonResponse;
use Validator;
use App\Http\Controllers\Api\BaseController;
class BookingController extends BaseController
{
    public function __construct(
        private BookingService $bookings,
        private NotificationService $notificationService
    ) {}

    public function accept(Request $request, $id): JsonResponse
    { 
        $booking = Booking::find($id);
        if(!$booking) {
            return $this->sendError('Booking not found.');
        }
        $updated = $this->bookings->accept($booking);
        if ($updated['error'] === true) {
            return $this->sendError($updated['message']);
        }
        return $this->sendResponse($updated, 'Booking accepted successfully.'); 
    }

    public function directStore(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'provider_id' => 'required|exists:users,id',
            'customer_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id', 
            'booking_address' => 'nullable|string|max:255',
            'booking_description' => 'nullable|string',
            'total_price' => 'required|numeric|min:0',
            'currency' => 'nullable|string|size:3',
            'slots' => 'required|array|min:1',
            'slots.*.service_date' => 'required|date_format:Y-m-d',
            'slots.*.start_time' => 'required|date_format:H:i',
            'slots.*.end_time' => 'required|date_format:H:i|after:slots.*.start_time',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }

        $data = $validator->validated();

        // Use customer's address if booking_address is not provided
        if (empty($data['booking_address'])) {
            $customer = \App\Models\User::find($data['customer_id']);
            $data['booking_address'] = $customer ? ($customer->address ?? 'Direct Booking') : 'Direct Booking';
        }

        try {
            $result = $this->bookings->directCreate($data);
            
            if ($result['error']) {
                return $this->sendError($result['message'], 422);
            }

            $this->notificationService->notifyDirectBookingCreated($result['booking']);

            return $this->sendResponse($result['booking'], 'Direct booking created and accepted successfully.');
        } catch (\Exception $e) {
            Log::error('Direct booking failed: ' . $e->getMessage());
            return $this->sendError('Failed to create direct booking.', 500);
        }
    }

    public function reject(Request $request, $id): JsonResponse
    {         
        $booking = Booking::find($id);
        if(!$booking) {
            return $this->sendError('Booking not found.');
        }
        $updated = $this->bookings->reject($booking);
        
        if ($updated['error'] === true) {
            return $this->sendError($updated['message']);
        }
        return $this->sendResponse($updated, 'Booking rejected successfully.'); 
    }
    public function start(Request $request, $id): JsonResponse
    {
        $booking = Booking::find($id);
        if(!$booking) {
            return $this->sendError('Booking not found.');
        }
        $updated = $this->bookings->start($booking);
        if ($updated['error'] === true) {
            return $this->sendError($updated['message']);
        }
        return $this->sendResponse($updated, 'Booking started successfully.'); 
    }
    public function complete(Request $request, $id): JsonResponse
    {
        $booking = Booking::find($id);
        if(!$booking) {
            return $this->sendError('Booking not found.');
        }
        $updated = $this->bookings->complete($booking);
        if ($updated['error'] === true) {
            return $this->sendError($updated['message']);
        }
        return $this->sendResponse($updated, 'Booking completed successfully.'); 
    }

    public function job(): JsonResponse
    {
        $job = $this->bookings->job(auth()->user()->id);
         
        return $this->sendResponse($job, 'Job created successfully.'); 
    }

    public function pending(): JsonResponse
    {
        $pending = $this->bookings->pendingBookingsProvider(auth()->user()->id);
        return $this->sendResponse($pending, 'Pending bookings.');
    }
    public function upcoming(): JsonResponse
    {
        $upcoming = $this->bookings->upcomingBookingsProvider(auth()->user()->id);
        return $this->sendResponse($upcoming, 'Upcoming bookings.'); 
    }

    public function onGoing(): JsonResponse
    {
        $ongoing = $this->bookings->ongoingBookingsProvider(auth()->user()->id);
        return $this->sendResponse($ongoing, 'Ongoing bookings.'); 
    }

    public function completed(): JsonResponse
    {
        $completed = $this->bookings->completedBookingsProvider(auth()->user()->id);
        return $this->sendResponse($completed, 'Completed bookings.'); 
    }

    public function totalAmount(): JsonResponse
    {
        $amount = $this->bookings->totalAmountProvider(auth()->user()->id);
        return $this->sendResponse($amount, 'Total amount.'); 
    }
}
