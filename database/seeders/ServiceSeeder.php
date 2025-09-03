<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
        [
            'icon' => 'assets/images/services/cleaning.png',
            'name' => 'Cleaning',
            'slug' => Str::slug('Cleaning'),
        ],
        [
            'icon' => 'assets/images/services/carpenter.png',
            'name' => 'Carpenter',
            'slug' => Str::slug('Carpenter'),
        ],
        [
            'icon' => 'assets/images/services/laundry.png',
            'name' => 'Laundry',
            'slug' => Str::slug('Laundry'),
        ],
        [
            'icon' => 'assets/images/services/painting.png',
            'name' => 'Painting',
            'slug' => Str::slug('Painting'),
        ],
        [
            'icon' => 'assets/images/services/logistics.png',
            'name' => 'Logistics',
            'slug' => Str::slug('Logistics'),
        ],
        [
            'icon' => 'assets/images/services/cooking.png',
            'name' => 'Cooking',
            'slug' => Str::slug('Cooking'),
        ],
        [
            'icon' => 'assets/images/services/electric_work.png',
            'name' => 'Electric Work',
            'slug' => Str::slug('Electric Work'),
        ], 
        [
            'icon' => 'assets/images/services/plumbing.png',
            'name' => 'Plumbing',
            'slug' => Str::slug('Plumbing'),
        ],
        [
            'icon' => 'assets/images/services/beauty.png',
            'name' => 'Beauty',
            'slug' => Str::slug('Beauty'),
        ],
        [
            'icon' => 'assets/images/services/technician.png',
            'name' => 'Technician',
            'slug' => Str::slug('Technician'),
        ],
        [
            'icon' => 'assets/images/services/ac_repair.png',
            'name' => 'AC repair',
            'slug' => Str::slug('AC repair'),
        ],
        [
            'icon' => 'assets/images/services/baking.png',
            'name' => 'Baking',
            'slug' => Str::slug('Baking'),
        ],
        [
            'icon' => 'assets/images/services/gardener.png',
            'name' => 'Gardener',
            'slug' => Str::slug('Gardener'),
        ],
        [
            'icon' => 'assets/images/services/man_saloon.png',
            'name' => 'Man\'s saloon',
            'slug' => Str::slug('Man\'s saloon'),
        ],
    ];



        DB::table('services')->insert($services);
    }
}
