<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\ProviderRepository;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Validator;
class ProviderController extends BaseController
{
    protected $providerRepo;

    public function __construct(ProviderRepository $providerRepo)
    {
        $this->providerRepo = $providerRepo;
    }


    public function providers(Request $request)
    {
        $filters = $request->only([
            'provider_name',
            'service_name',
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
