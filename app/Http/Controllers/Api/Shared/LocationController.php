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
        try {
            //code...
            $countries = DB::table('countries')
                ->leftJoin('currencies', 'countries.id', '=', 'currencies.country_id')
                ->select(
                    'countries.id', 
                    'countries.name', 
                    'countries.emoji', 
                    'countries.iso2', 
                    'countries.phone_code',
                    'currencies.code as currency_code',
                    'currencies.symbol as currency_symbol',
                    'currencies.name as currency_name'
                )
                ->where('countries.status', 1)
                ->orderBy('countries.name')
                ->get()
                ->map(function($country) {
                    // Local PNG Flag URL
                    $country->flag_url = "assets/images/flags/" . strtolower($country->iso2) . ".png";
                    return $country;
                });
            
            return $this->sendResponse($countries, 'Countries fetched successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Something went wrong in countries api call.');
            //throw $th;
        }
        // Querying directly from DB to include the new 'emoji' column and joining currencies
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
