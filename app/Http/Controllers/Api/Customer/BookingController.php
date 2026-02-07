<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Api\Booking\StoreBookingRequest;
use App\Models\Booking;
use App\Services\Booking\BookingService;
use Illuminate\Http\JsonResponse;
use Validator;
use App\Http\Controllers\Api\BaseController;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends BaseController
{
    public function __construct(private BookingService $bookingsService) {}

    /**
     * Extend a booking time
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function extend(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'booking_working_minutes' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }

        try {
            return DB::transaction(function () use ($request, $id) {
                $booking = Booking::find($id);

                if (!$booking) {
                    return $this->sendError('Booking not found', 404);
                }

                if ($booking->customer_id != auth()->id()) {
                    return $this->sendError('Unauthorized access.', 403);
                }

                // if (!in_array($booking->status, ['confirmed', 'in_progress'])) {
                //     return $this->sendError('Only confirmed or in-progress bookings can be extended.', 422);
                // }

                $duration = $request->input('booking_working_minutes');
                $extensionPrice = $request->input('price');

                // Calculate service charges based on the provided price
                $commissionPercentage = (float) Setting::get('service_charge_percentage', 25) ; 
                $serviceCharges = ($extensionPrice * $commissionPercentage) / 100;

                // Update the booking
                $booking->increment('booking_working_minutes', $duration);
                $booking->increment('total_price', $extensionPrice);
                $booking->increment('service_charges', $serviceCharges);

                return $this->sendResponse($booking->refresh(), 'Booking extended successfully.');
            });
        } catch (\Exception $e) {
            Log::error('Booking extension failed: ' . $e->getMessage());
            return $this->sendError('Failed to extend booking.', 500);
        }
    }

    // Customer creates booking & pays (Stripe)
    public function store(StoreBookingRequest $request): JsonResponse
    {
         
        $booking = $this->bookingsService->create($request->validated());
         
        if ($booking['error'] === true) {
            return $this->sendError($booking['message']);
        }
        return $this->sendResponse($booking, 'Booking created successfully.'); 
    }

    // Show booking
    public function show($id): JsonResponse
    {
        $booking = Booking::with(['slots', 'customer', 'provider', 'providerService.service','latestPendingReschedule'])->find($id);

        if (!$booking) {
            return $this->sendError('Booking not found', 404);
        }
        
        $booking = $booking->load('slots','customer','provider','providerService.service');
        
        // Add late status for upcoming bookings
        if ($booking->status === 'confirmed') {
            $lateCheck = $this->bookingsService->isProviderLate($booking);
            $booking->setAttribute('is_provider_late', $lateCheck['is_late']);
            $booking->setAttribute('late_status', $lateCheck);
        }
        
        return $this->sendResponse($booking, 'Booking retrieved successfully.'); 
    }

    /**
     * Cancel a booking
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel(Request $request,  $id)
    {
        $validator = Validator::make($request->all(), [
            'cancelled_reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $validated = $validator->validated();
        $booking = Booking::with('slots')->find($id);
        if (!$booking) {
            return $this->sendError('Booking not found', 404);
        }
        $data = $this->bookingsService->cancel($booking, $validated['cancelled_reason']);
        // Return a success response
        return $this->sendResponse($data, 'Booking cancelled successfully.');
    }
    public function requestReschedule(Request $request, $id)
    {
        $booking = Booking::with('slots', 'provider', 'customer','providerService.service','latestPendingReschedule')->find($id);
        if (!$booking) {
            return $this->sendError('Booking not found', 404);
        }
          
        $data = $this->bookingsService->requestReschedule($booking, $request->new_slots);
        if ($data['error'] === true) {
            return $this->sendError($data['message']);
        }
         
        return $this->sendResponse([
            'booking'    => $data['booking'],
            'reschedule' => $data['reschedule'],
        ], 'Reschedule request sent.');
    }

    public function respondReschedule(Request $request, $id)
    {
        $booking = Booking::with('slots', 'provider', 'customer','providerService.service','latestPendingReschedule')->find($id);
        if (!$booking) {
            return $this->sendError('Booking not found', 404);
        }
         
        $data = $this->bookingsService->respondReschedule($booking, $request->action);
        if ($data['error'] === true) {
            return $this->sendError($data['message']);
        } 
        return $this->sendResponse([
            'booking'    => $data['booking'],
            'reschedule' => $data['reschedule'],
        ], 'Reschedule response handled.');
    }

    public function pending(): JsonResponse
    {
        $pending = $this->bookingsService->pendingBookingsCustomer(auth()->user()->id);
        return $this->sendResponse($pending, 'Pending bookings.');
    }
    public function upcoming(): JsonResponse
    {
        $upcoming = $this->bookingsService->upcomingBookingsCustomer(auth()->user()->id);
        return $this->sendResponse($upcoming, 'Upcoming bookings.'); 
    }

    public function completed(): JsonResponse
    {
         
        $upcoming = $this->bookingsService->completedBookingsCustomer(auth()->user()->id);
        return $this->sendResponse($upcoming, 'Completed bookings.'); 
    }

    public function cancelled(): JsonResponse
    {
        $upcoming = $this->bookingsService->cancelledBookingsCustomer(auth()->user()->id);
        return $this->sendResponse($upcoming, 'Cancelled bookings.'); 
    }

    public function processPayment(Request $request, $id): JsonResponse
    {
        $data = $request->validate([
            'user_payment_method_id' => 'nullable|integer|exists:user_payment_methods,id',
        ]);

        $payment = $this->bookingsService->processPayment($id, $data['user_payment_method_id'] ?? null);
        if ($payment['error'] === true) {
            return $this->sendError($payment['message']);
        } 
        return $this->sendResponse([], 'Payment processed successfully.'); 
    }

    public function onGoing(): JsonResponse
    {
        $ongoing = $this->bookingsService->ongoingBookingsCustomer(auth()->user()->id);
        return $this->sendResponse($ongoing, 'Ongoing bookings.'); 
    }

    /**
     * Check if provider is late for a booking
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function checkProviderLate($id): JsonResponse
    {
        $booking = Booking::with('slots')->find($id);
        
        if (!$booking) {
            return $this->sendError('Booking not found', 404);
        }

        // Ensure customer owns this booking
        if ($booking->customer_id !== auth()->id()) {
            return $this->sendError('Unauthorized access to this booking.', 403);
        }

        $lateCheck = $this->bookingsService->isProviderLate($booking);
        
        return $this->sendResponse($lateCheck, 'Provider late status checked.');
    }

    /**
     * Handle late action for a booking
     * 
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function handleLateAction(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:wait,reschedule,escalate',
            // 'new_slots' => 'required_if:action,reschedule|array',
            // 'new_slots.*.service_date' => 'required_if:action,reschedule|date_format:Y-m-d|after_or_equal:today',
            // 'new_slots.*.start_time' => 'required_if:action,reschedule|date_format:H:i',
            // 'new_slots.*.end_time' => 'required_if:action,reschedule|date_format:H:i|after:new_slots.*.start_time',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }

        $booking = Booking::with('slots')->find($id);
        
        if (!$booking) {
            return $this->sendError('Booking not found', 404);
        }

        // Ensure customer owns this booking
        if ($booking->customer_id != auth()->id()) {
            return $this->sendError('Unauthorized access to this booking.', 403);
        }

        $action = $request->input('action');
        $newSlots = $request->input('new_slots');

        $result = $this->bookingsService->handleLateAction($booking, $action, $newSlots);

        if ($result['error'] === true) {
            return $this->sendError($result['message'], 400);
        }

        return $this->sendResponse($result, 'Late action handled successfully.');
    }
}
