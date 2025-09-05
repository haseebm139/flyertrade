<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Api\Booking\StoreBookingRequest;
use App\Models\Booking;
use App\Services\Booking\BookingService;
use Illuminate\Http\JsonResponse;

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
        $booking = Booking::with('slots')->find($id);

        if (!$booking) {
            return $this->sendError('Booking not found', 404);
        }
        return $this->sendResponse($booking->load('slots'), 'Booking retrieved successfully.'); 
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
}
