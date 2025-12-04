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
        // Get faker instance - handle AWS environment where $this->faker might be null
        if (!isset($this->faker) || $this->faker === null) {
            try {
                $this->faker = \Illuminate\Support\Facades\App::make(\Faker\Generator::class);
            } catch (\Exception $e) {
                // Fallback: Create Faker instance directly if service container fails
                $this->faker = \Faker\Factory::create();
            }
        }
        $faker = $this->faker;
        
        // Random role from roles table
        $role = Role::inRandomOrder()->first();

        return [
            'name' => $faker->name(),
            'email' => $faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // default password
            'remember_token' => Str::random(10),
            'role_id' => $role ? strtolower($role->name) : 'customer',
            'user_type' => $role ? strtolower($role->name) : 'customer',
            'phone' => $faker->phoneNumber(),

            'country' => $faker->country(),
            'city' => $faker->city(),
            'state' => $faker->state(),
            'zip' => $faker->postcode(),
            'address' => $faker->streetAddress(),
            'latitude' => $faker->latitude(),
            'longitude' => $faker->longitude(),

        ];
    }
}
