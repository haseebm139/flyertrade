<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\ProviderRepository;
use App\Services\Booking\BookingService;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Validator;
class ProviderController extends BaseController
{
    protected $providerRepo;
    protected $bookingService;

    public function __construct(ProviderRepository $providerRepo, BookingService $bookingService)
    {
        $this->providerRepo = $providerRepo;
        $this->bookingService = $bookingService;
    }


    public function providers(Request $request)
    {
        $filters = $request->only([
            'search',
            'provider_name',
            'service_name',
            'service_id',
            'min_price',
            'max_price',
            'min_rating',
            'latitude',
            'longitude',
            'distance',
            'sort_by',
            'per_page',

        ]);


        $providers = $this->providerRepo->getProviders($filters,auth()->user()->id, $request->get('limit', 10));

        return $this->sendResponse($providers, 'Providers');
    }

    public function show(string $id)
    {
        $result = $this->providerRepo->providerProfile($id);
        return $this->sendResponse($result, 'Provider profile.');
    }

    public function bookedSlots(string $id)
    {
        $slots = $this->providerRepo->getBookedSlots((int) $id);
        return $this->sendResponse($slots, 'Provider booked slots.');
    }

    public function bookedSlotsMe()
    {
        $slots = $this->providerRepo->getBookedSlots(auth()->id());
        return $this->sendResponse($slots, 'Your booked slots.');
    }

    public function checkAvailability(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'slots' => 'required|array|min:1',
            'slots.*.service_date' => 'required|date_format:Y-m-d',
            'slots.*.start_time' => 'required|date_format:H:i',
            'slots.*.end_time' => 'required|date_format:H:i|after:slots.*.start_time',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }

        foreach ($request->slots as $slot) {
            $status = $this->bookingService->checkAvailability($slot, (int)$id);
            if ($status !== 'available') {
                return $this->sendError("Provider is not available on {$slot['service_date']} between {$slot['start_time']} - {$slot['end_time']}.", 422);
            }
        }

        return $this->sendResponse(true, 'Provider is available for the selected slots.');
    }

    public function checkAvailabilityMe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slots' => 'required|array|min:1',
            'slots.*.service_date' => 'required|date_format:Y-m-d',
            'slots.*.start_time' => 'required|date_format:H:i',
            'slots.*.end_time' => 'required|date_format:H:i|after:slots.*.start_time',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }

        foreach ($request->slots as $slot) {
            $status = $this->bookingService->checkAvailability($slot, auth()->id());
            if ($status !== 'available') {
                return $this->sendError("You are not available on {$slot['service_date']} between {$slot['start_time']} - {$slot['end_time']}.", 422);
            }
        }

        return $this->sendResponse(true, 'You are available for the selected slots.');
    }

    public function toggle(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'provider_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $result = $this->providerRepo->toggleBookmark(auth()->id(), $request->provider_id);

        return $this->sendResponse([], $result['message']);
    }

    public function bookmarks()
    {
        $data = $this->providerRepo->getBookmarks(auth()->id());
        return $this->sendResponse($data, 'Bookmarked providers fetched successfully.');

    }

}
