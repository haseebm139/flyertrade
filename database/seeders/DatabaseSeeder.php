<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Global Data (Countries, States, Cities, Flags, Currencies)
        $this->call(GlobalLocationSeeder::class);

        
        // 2. Roles and Permissions
        $this->call(PermissionSeeder::class);
        
        // 3. Admin Settings (Commission, Currency, etc.)
        $this->call(SettingSeeder::class);
        
        // 4. Testing Data (Users, Services, Bookings)
        $this->call(TestingFlowSeeder::class);
    }
}
