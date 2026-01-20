<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Setting::set('service_charge_percentage', '25', 'financial');
        \App\Models\Setting::set('currency', 'AED', 'financial');
    }
}
