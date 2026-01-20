<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate existing data to avoid duplicates if re-running
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \App\Models\City::truncate();
        \App\Models\Country::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $locations = [
            [
                'name' => 'United Arab Emirates',
                'code' => 'AE',
                'phone_code' => '+971',
                'cities' => ['Dubai', 'Abu Dhabi', 'Sharjah', 'Ajman', 'Fujairah', 'Ras Al Khaimah', 'Umm Al Quwain', 'Al Ain']
            ],
            [
                'name' => 'Pakistan',
                'code' => 'PK',
                'phone_code' => '+92',
                'cities' => ['Karachi', 'Lahore', 'Islamabad', 'Rawalpindi', 'Faisalabad', 'Multan', 'Peshawar', 'Quetta', 'Sialkot', 'Gujranwala', 'Hyderabad']
            ],
            [
                'name' => 'Saudi Arabia',
                'code' => 'SA',
                'phone_code' => '+966',
                'cities' => ['Riyadh', 'Jeddah', 'Mecca', 'Medina', 'Dammam', 'Khobar', 'Abha', 'Tabuk', 'Taif']
            ],
            [
                'name' => 'United Kingdom',
                'code' => 'GB',
                'phone_code' => '+44',
                'cities' => ['London', 'Manchester', 'Birmingham', 'Glasgow', 'Liverpool', 'Edinburgh', 'Bristol', 'Leeds']
            ],
            [
                'name' => 'United States',
                'code' => 'US',
                'phone_code' => '+1',
                'cities' => ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 'Philadelphia', 'San Antonio', 'San Diego']
            ],
            [
                'name' => 'India',
                'code' => 'IN',
                'phone_code' => '+91',
                'cities' => ['Mumbai', 'Delhi', 'Bangalore', 'Hyderabad', 'Ahmedabad', 'Chennai', 'Kolkata', 'Surat']
            ],
            [
                'name' => 'Qatar',
                'code' => 'QA',
                'phone_code' => '+974',
                'cities' => ['Doha', 'Al Wakrah', 'Al Rayyan', 'Al Khor', 'Madinat ash Shamal']
            ],
            [
                'name' => 'Oman',
                'code' => 'OM',
                'phone_code' => '+968',
                'cities' => ['Muscat', 'Salalah', 'Sohar', 'Nizwa', 'Sur']
            ],
            [
                'name' => 'Kuwait',
                'code' => 'KW',
                'phone_code' => '+965',
                'cities' => ['Kuwait City', 'Al Ahmadi', 'Hawalli', 'Salmiya', 'Jahra']
            ],
            [
                'name' => 'Bahrain',
                'code' => 'BH',
                'phone_code' => '+973',
                'cities' => ['Manama', 'Riffa', 'Muharraq', 'Hamad Town', 'Isa Town']
            ],
            [
                'name' => 'Canada',
                'code' => 'CA',
                'phone_code' => '+1',
                'cities' => ['Toronto', 'Vancouver', 'Montreal', 'Calgary', 'Ottawa', 'Edmonton', 'Winnipeg']
            ],
            [
                'name' => 'Australia',
                'code' => 'AU',
                'phone_code' => '+61',
                'cities' => ['Sydney', 'Melbourne', 'Brisbane', 'Perth', 'Adelaide', 'Gold Coast', 'Canberra']
            ],
        ];

        foreach ($locations as $loc) {
            $country = \App\Models\Country::create([
                'name' => $loc['name'],
                'code' => $loc['code'],
                'phone_code' => $loc['phone_code'],
            ]);

            foreach ($loc['cities'] as $cityName) {
                \App\Models\City::create([
                    'country_id' => $country->id,
                    'name' => $cityName,
                ]);
            }
        }
    }
}
