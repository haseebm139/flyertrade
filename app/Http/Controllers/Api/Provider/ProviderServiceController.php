<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\BaseController;

use App\Services\Provider\ProviderServicesService;
use Validator;
class ProviderServiceController extends BaseController
{

    protected $service;
    /**
     * Constructor for the class.
     *
     * @param ProviderServicesService $profileService The profile service instance.
     */
    public function __construct(ProviderServicesService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_profile_id = auth()->user()->providerProfile->id;
        if (!$user_profile_id) {
            return $this->sendError('User profile not found.');
        }
        $data =  $this->service->get($user_profile_id);

        return $this->sendResponse($data, 'Provider Service fetched successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Services
            'services'                   => 'nullable|array',
            'services.about'          => 'nullable|string|max:1000',
            'services.service_id'      => 'required|exists:services,id',
            'services.title'   => 'nullable|string|max:255',
            'services.description' => 'nullable|string',
            'services.staff_count' => 'nullable|integer|min:1',
            'services.rate_min'        => 'nullable|numeric|min:0',
            'services.rate_max'        => 'nullable|numeric|min:0',
            'services.is_primary'      => 'boolean',

            // Media
            'services.photos.*'        => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'services.videos.*'        => 'nullable|file|mimes:mp4,mov,avi|max:10240',

            // Certificates
            'services.certificates.*'        => 'nullable|file|mimes:jpg,jpeg,png|max:5120',

        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $data = $validator->validated();
        $result = $this->service->create($data, auth()->user());

        if (isset($result['error']) && $result['error'] === true) {
            return $this->sendError($result['message']);
        }
        return $this->sendResponse($result, 'Provider Service saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {


        $validator = Validator::make($request->all(), [
            'services'                 => 'nullable|array',
            'services.about'           => 'nullable|string|max:1000',
            'services.service_id'      => 'nullable|exists:services,id',
            'services.title'           => 'nullable|string|max:255',
            'services.description'     => 'nullable|string',
            'services.staff_count'     => 'nullable|integer|min:1',
            'services.rate_min'        => 'nullable|numeric|min:0',
            'services.rate_max'        => 'nullable|numeric|min:0',
            'services.is_primary'      => 'boolean',

            // Media
            'services.photos.*'        => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'services.videos.*'        => 'nullable|file|mimes:mp4,mov,avi|max:10240',

            // Certificates
            'services.certificates.*'  => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        $data = $validator->validated();
        $result = $this->service->update($data, auth()->user(), $id);

        if (isset($result['error']) && $result['error'] === true) {
            return $this->sendError($result['message']);
        }

        return $this->sendResponse($result, 'Provider Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->service->delete(auth()->user(), $id);

        if (isset($result['error']) && $result['error'] === true) {
            return $this->sendError($result['message']);
        }

        return $this->sendResponse([], 'Provider Service deleted successfully.');
    }
}
