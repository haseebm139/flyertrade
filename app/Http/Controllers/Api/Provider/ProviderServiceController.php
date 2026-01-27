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
            'services.rate_mid'        => 'nullable|numeric|min:0',
            'services.rate_max'        => 'nullable|numeric|min:0',
            'services.is_primary'      => 'boolean',
            'services.show_certificate' => 'boolean',

            // Media
            'services.photos.*'        => 'nullable|file|mimes:jpg,jpeg,png|max:10240',
            'services.videos.*'        => 'nullable|file|mimes:mp4,mov,avi|max:10240',

            // Certificates
            'services.certificates.*'        => 'nullable|file|mimes:jpg,jpeg,png|max:10240',

        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        
        $data = $validator->validated();
        
        // Add files from request (validated() doesn't include files in form-data)
        // Postman format: services[photos], services[videos], services[certificates]
        if ($request->hasFile('services.photos')) {
            $data['services']['photos'] = $request->file('services.photos');
        }
        if ($request->hasFile('services.videos')) {
            $data['services']['videos'] = $request->file('services.videos');
        }
        if ($request->hasFile('services.certificates')) {
            $data['services']['certificates'] = $request->file('services.certificates');
        }
        
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
            'services.rate_mid'        => 'nullable|numeric|min:0',
            'services.rate_max'        => 'nullable|numeric|min:0',
            'services.is_primary'      => 'boolean',
            'services.show_certificate' => 'boolean',

            // Media
            'services.photos.*'        => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'services.videos.*'        => 'nullable|file|mimes:mp4,mov,avi|max:10240',
            'services.delete_photos'   => 'nullable',
            'services.delete_videos'   => 'nullable',
            'services.delete_certificates'   => 'nullable',

            // Certificates
            'services.certificates.*'  => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        $data = $validator->validated();
        
        // Parse delete arrays from string format (Postman sends as "[7,8,9]")
        if (isset($data['services']['delete_photos'])) {
            $deletePhotos = $data['services']['delete_photos'];
            if (is_string($deletePhotos)) {
                // Remove brackets and split by comma
                $deletePhotos = trim($deletePhotos, '[]');
                $data['services']['delete_photos'] = array_filter(
                    array_map('intval', explode(',', $deletePhotos))
                );
            } elseif (!is_array($deletePhotos)) {
                unset($data['services']['delete_photos']);
            }
        }
        
        if (isset($data['services']['delete_videos'])) {
            $deleteVideos = $data['services']['delete_videos'];
            if (is_string($deleteVideos)) {
                // Remove brackets and split by comma
                $deleteVideos = trim($deleteVideos, '[]');
                $data['services']['delete_videos'] = array_filter(
                    array_map('intval', explode(',', $deleteVideos))
                );
            } elseif (!is_array($deleteVideos)) {
                unset($data['services']['delete_videos']);
            }
        }
        
        if (isset($data['services']['delete_certificates'])) {
            $deleteCerts = $data['services']['delete_certificates'];
            if (is_string($deleteCerts)) {
                // Remove brackets and split by comma
                $deleteCerts = trim($deleteCerts, '[]');
                $data['services']['delete_certificates'] = array_filter(
                    array_map('intval', explode(',', $deleteCerts))
                );
            } elseif (!is_array($deleteCerts)) {
                unset($data['services']['delete_certificates']);
            }
        }
        
        // Add files from request (validated() doesn't include files in form-data)
        // Postman format: services[photos], services[videos], services[certificates]
        if ($request->hasFile('services.photos')) {
            $data['services']['photos'] = $request->file('services.photos');
        }
        if ($request->hasFile('services.videos')) {
            $data['services']['videos'] = $request->file('services.videos');
        }
        if ($request->hasFile('services.certificates')) {
            $data['services']['certificates'] = $request->file('services.certificates');
        }
         
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

        $message = $result['message'] ?? 'Service deleted successfully.';
        return $this->sendResponse([], $message);
    }
}
