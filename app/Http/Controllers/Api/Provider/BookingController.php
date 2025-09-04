<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Services\Booking\BookingService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Api\BaseController;
class BookingController extends BaseController
{
    public function __construct(private BookingService $bookings) {}

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
}
