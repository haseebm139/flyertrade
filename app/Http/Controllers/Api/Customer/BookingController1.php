<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Api\Booking\StoreBookingRequest;
use App\Http\Requests\Api\Booking\ProcessPaymentRequest;
use App\Models\Booking;
use App\Services\Booking\BookingService;
use Illuminate\Http\JsonResponse;
use Validator;
use App\Http\Controllers\Api\BaseController;
class BookingController extends BaseController
{
    public function __construct(private BookingService $bookingsService) {}

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
        $booking = Booking::with(['slots', 'customer', 'provider', 'providerService.service'])->find($id);

        if (!$booking) {
            return $this->sendError('Booking not found', 404);
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
        $booking = Booking::with('slots')->find($id);
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
        $booking = Booking::with('slots')->find($id);
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

    /**
     * Process payment for a booking
     * 
     * @param ProcessPaymentRequest $request
     * @param Booking $booking
     * @return JsonResponse
     */
    public function processPayment(ProcessPaymentRequest $request, Booking $booking): JsonResponse
    {
        // Authorization check
        if ($booking->customer_id !== auth()->id()) {
            return $this->sendError('Unauthorized access to booking', 403);
        }

        $payment = $this->bookingsService->processPayment($booking, $request->payment_method_id);
        
        if ($payment['error'] === true) {
            // Return appropriate status code based on error type
            $statusCode = match($payment['code'] ?? '') {
                'ALREADY_PAID', 'INVALID_STATUS' => 400,
                'UNAUTHORIZED' => 403,
                'CARD_DECLINED', 'PAYMENT_FAILED' => 402,
                default => 422
            };
            
            return $this->sendError($payment['message'], $statusCode);
        } 
        
        // Handle requires_action (3D Secure)
        if ($payment['requires_action'] ?? false) {
            return $this->sendResponse([
                'payment_intent_id' => $payment['payment_intent_id'],
                'payment_status' => $payment['payment_status'],
                'client_secret' => $payment['client_secret'],
                'requires_action' => true,
                'booking' => $payment['booking'],
            ], 'Payment requires additional authentication. Please complete 3D Secure verification.'); 
        }
        
        return $this->sendResponse([
            'payment_intent_id' => $payment['payment_intent_id'],
            'payment_status' => $payment['payment_status'],
            'booking' => $payment['booking'],
        ], $payment['message'] ?? 'Payment processed successfully.'); 
    }

    public function onGoing(): JsonResponse
    {
        $ongoing = $this->bookingsService->ongoingBookingsCustomer(auth()->user()->id);
        return $this->sendResponse($ongoing, 'Ongoing bookings.'); 
    }
}
