<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    /**
     * Run the database seeds.
    */
    public function run(): void
    {

        // Ensure roles exist
        $roles = [
            'admin',
            'provider',
            'customer',
            'multi',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Create 1 Admin
        $adminRole = Role::where('name', 'admin')->first();
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'role_id' => $adminRole->name,
            'user_type' => $adminRole->name,
            'password' => Hash::make('password'),
        ])->assignRole($adminRole->name);

        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'demo@demo.com',
            'role_id' => $adminRole->name,
            'user_type' => $adminRole->name,
            'password' => Hash::make('password'),
        ])->assignRole($adminRole->name);

        // Create 5 Providers
        $providerRole = Role::where('name', 'provider')->first();
        User::factory(5)->create([
            'role_id' => $providerRole->name,
            'user_type' => $providerRole->name,
        ])->each(function ($user) use ($providerRole) {
            $user->assignRole($providerRole->name);
        });

        // Create 5 Customers
        $customerRole = Role::where('name', 'customer')->first();
        User::factory(5)->create([
            'role_id' => $customerRole->name,
            'user_type' => $customerRole->name,
        ])->each(function ($user) use ($customerRole) {
            $user->assignRole($customerRole->name);
        });

        // Create 2 Multi-role users
        $multiRole = Role::where('name', 'multi')->first();
        User::factory(2)->create([
            'role_id' => $multiRole->name,
            'user_type' => $multiRole->name,
        ])->each(function ($user) use ($multiRole) {
            // Assign multiple roles here
            $user->assignRole(['provider', 'customer']);
        });
    }
}
