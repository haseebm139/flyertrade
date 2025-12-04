<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\ProfileRequest;
use App\Services\Customer\CustomerProfileService;
use App\Http\Resources\Shared\UserResource;
use App\Models\User;

class ProfileController extends BaseController
{
    protected $profileService;

    /**
     * Constructor for the class.
     *
     * @param CustomerProfileService $profileService The profile service instance.
     */
    public function __construct(CustomerProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Show a user profile by ID.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->sendError('Profile not found.', 404);
        }

        // Load provider profile if user is a provider
        if ($user->hasRole('provider') || $user->user_type === 'provider' || $user->user_type === 'multi') {
            $user->load([
                'providerProfile',
                'providerProfile.services',
                'providerProfile.services.service',
                'providerProfile.services.media',
                'providerProfile.services.certificates'
            ]);
        }

        return $this->sendResponse($user, 'Profile retrieved successfully.');
    }

    /**
     * Store/Update user profile.
     *
     * @param ProfileRequest $request The request instance.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProfileRequest $request)
    {
        $user = auth()->user();
        $result = $this->profileService->updateProfile($request->validated(), $user);

        // Load provider profile if user is a provider
        if ($user->hasRole('provider') || $user->user_type === 'provider' || $user->user_type === 'multi') {
            $result->load([
                'providerProfile',
                'providerProfile.services',
                'providerProfile.services.service',
                'providerProfile.services.media',
                'providerProfile.services.certificates'
            ]);
        }

        return $this->sendResponse($result, 'Profile updated successfully.');
    }

    /**
     * Get current authenticated user profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfile()
    {
        $user = auth()->user();
        
        // Load provider profile if user is a provider
        if ($user->hasRole('provider') || $user->user_type === 'provider' || $user->user_type === 'multi') {
            $user->load([
                'providerProfile',
                'providerProfile.services',
                'providerProfile.services.service',
                'providerProfile.services.media',
                'providerProfile.services.certificates'
            ]);
        }

        $result = $this->profileService->getProfile($user);
        return $this->sendResponse($result, 'Profile retrieved successfully.');
    }
}

