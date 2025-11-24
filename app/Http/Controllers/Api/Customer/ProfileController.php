<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Customer\CustomerProfileRequest;
use App\Services\Customer\CustomerOnlyProfileService;
use App\Http\Resources\Shared\UserResource;
use App\Models\User;

class ProfileController extends BaseController
{
    protected $profileService;

    /**
     * Constructor for the class.
     *
     * @param CustomerOnlyProfileService $profileService The profile service instance.
     */
    public function __construct(CustomerOnlyProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Show a customer profile by ID.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $customer = $this->profileService->getProfileById($id);

        if (!$customer) {
            return $this->sendError('Customer profile not found.', 404);
        }

        return $this->sendResponse(new UserResource($customer), 'Customer profile retrieved successfully.');
    }

    /**
     * Store/Update customer profile.
     *
     * @param CustomerProfileRequest $request The request instance.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CustomerProfileRequest $request)
    {
        $result = $this->profileService->updateProfile($request->validated(), auth()->user());

        return $this->sendResponse(new UserResource($result), 'Customer profile updated successfully.');
    }

    /**
     * Get current customer profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfile()
    {
        $result = $this->profileService->getProfile(auth()->user());
        return $this->sendResponse([ 
            'user'  => $user->load('providerProfile'), // Load roles for response
        ], 'Customer profile retrieved successfully.');
    }
}
