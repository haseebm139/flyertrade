<?php

namespace App\Http\Controllers\Api\Shared;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class LocationController extends BaseController
{
    /**
     * Get all countries
     */
    public function countries()
    {
        // Querying directly from DB to include the new 'emoji' column
        $countries = DB::table('countries')
            ->select('id', 'name', 'emoji', 'iso2', 'phone_code')
            ->where('status', 1)
            ->orderBy('name')
            ->get()
            ->map(function($country) {
                // PNG Flag URL
                $country->flag_url = "https://flagcdn.com/w160/" . strtolower($country->iso2) . ".png";
                return $country;
            });
        
        return $this->sendResponse($countries, 'Countries fetched successfully.');
    }

 

    /**
     * Get cities for a specific country
     */
    public function cities($countryId): JsonResponse
    {
        $cities = DB::table('cities')
            ->select('id', 'name', 'state_id')
            ->where('country_id', $countryId)
            ->orderBy('name')
            ->get();

        return $this->sendResponse($cities, 'Cities fetched successfully.');
    }

    /**
     * Get states for a specific country
     */
    public function states($countryId): JsonResponse
    {
        $states = DB::table('states')
            ->select('id', 'name')
            ->where('country_id', $countryId)
            ->orderBy('name')
            ->get();

        return $this->sendResponse($states, 'States fetched successfully.');
    }
}
