<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Spatie\Permission\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        // Random role from roles table
        $role = Role::inRandomOrder()->first();
        
        // Generate random data without faker
        $firstName = ['John', 'Jane', 'Mike', 'Sarah', 'David', 'Emily', 'Chris', 'Lisa', 'Tom', 'Amy'];
        $lastName = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Wilson', 'Moore'];
        $first = $firstName[array_rand($firstName)];
        $last = $lastName[array_rand($lastName)];
        $name = $first . ' ' . $last;
        
        $email = strtolower($first . '.' . $last . rand(1000, 9999) . '@example.com');
        
        $cities = ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 'Philadelphia', 'San Antonio', 'San Diego', 'Dallas', 'San Jose'];
        $states = ['NY', 'CA', 'IL', 'TX', 'AZ', 'PA', 'TX', 'CA', 'TX', 'CA'];
        $countries = ['USA', 'USA', 'USA', 'USA', 'USA', 'USA', 'USA', 'USA', 'USA', 'USA'];
        
        $cityIndex = array_rand($cities);
        $city = $cities[$cityIndex];
        $state = $states[$cityIndex];
        $country = $countries[$cityIndex];

        return [
            'name' => $name,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // default password
            'remember_token' => Str::random(10),
            'role_id' => $role ? strtolower($role->name) : 'customer',
            'user_type' => $role ? strtolower($role->name) : 'customer',
            'phone' => '+1' . rand(2000000000, 9999999999),

            'country' => $country,
            'city' => $city,
            'state' => $state,
            'zip' => rand(10000, 99999),
            'address' => rand(100, 9999) . ' Main Street',
            'latitude' => round(25 + (rand(0, 5000) / 100), 7), // USA latitude range
            'longitude' => round(-125 + (rand(0, 6000) / 100), 7), // USA longitude range

        ];
    }
}
