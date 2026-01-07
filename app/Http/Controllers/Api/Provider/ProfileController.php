<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Provider\CreateProviderProfileRequest;
use App\Services\Provider\ProviderProfileService;
use Validator;
class ProfileController extends BaseController
{
    protected $profileService;

    /**
     * Constructor for the class.
     *
     * @param ProviderProfileService $profileService The profile service instance.
     */
    public function __construct(ProviderProfileService $profileService)
    {
        $this->profileService = $profileService;
    }


    /**
     * Store a provider profile.
     *
     * @param CreateProviderProfileRequest $request The request instance.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateProviderProfileRequest $request)
    {
        // return $this->sendResponse($request->validated(), 'Provider profile saved successfully.');
         
        $result = $this->profileService->createOrUpdateProfile($request->validated(), auth()->user());
        if (isset($result['error']) && $result['error'] === true) {
            return $this->sendError($result['message']);
        }
        return $this->sendResponse($result, 'Provider profile saved successfully.');
    }

    public function getProfile()
    {
        $result = $this->profileService->getProfile(auth()->user());
        return $this->sendResponse($result->load('providerProfile'), 'profile retrieved successfully.');
    }
    public function changeAvailabilityStatus(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'availability_status' => 'required|in:fully_booked,available,not_available',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $result = $this->profileService->changeAvailabilityStatus($validator->validated(), auth()->user());

        if ($result['error'] ?? false) {
            return $this->sendError($result['message']);
        }

        return $this->sendResponse([], 'Provider profile saved successfully.');
    }

    public function createWorkingHours(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'working_hours' => 'required|array',
            'working_hours.*.day' => 'required|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'working_hours.*.start_time' => 'nullable|date_format:H:i',
            'working_hours.*.end_time' => 'nullable|date_format:H:i',
            'working_hours.*.is_active' => 'required|boolean',
        ]);

        $validator->after(function ($validator) use ($request) {
            foreach ($request->working_hours as $index => $dayData) {
                if (!empty($dayData['start_time']) && !empty($dayData['end_time'])) {
                    if ($dayData['end_time'] <= $dayData['start_time']) {
                        $validator->errors()->add(
                            "working_hours.$index.end_time",
                            "End time must be after start time for {$dayData['day']}."
                        );
                    }
                }
            }
        });

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }

        $data = $validator->validated();

        $result = $this->profileService->createOrUpdateWorkingHours($data, auth()->user());
        if ($result['error'] ?? false) {
            return $this->sendError("Something went wrong");
        }
        return $this->sendResponse([], 'Working hours saved successfully.', 201);
    }

    public function getWorkingHours()
    {
        $result = $this->profileService->workingHours(auth()->user());
        return $this->sendResponse($result, 'Working hours.');
    }
}
