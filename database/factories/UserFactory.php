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

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // default password
            'remember_token' => Str::random(10),
            'role_id' => $role ? strtolower($role->name) : null,
            'user_type' => $role ? strtolower($role->name) : null,
            'phone' => $this->faker->phoneNumber(),

            'country' => $this->faker->country(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'zip' => $this->faker->postcode(),
            'address' => $this->faker->streetAddress(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),

        ];
    }
}
