<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Provider\CreateProviderProfileRequest;
use App\Services\Provider\ProviderProfileService;
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
}
