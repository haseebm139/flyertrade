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
            'icon' => 'assets/images/services/carpenter.svg',
            'name' => 'Carpenter',
            'slug' => Str::slug('Carpenter'),
        ],
        [
            'icon' => 'assets/images/services/cleaning.svg',
            'name' => 'Cleaning',
            'slug' => Str::slug('Cleaning'),
        ],
        [
            'icon' => 'assets/images/services/cooking.svg',
            'name' => 'Cooking',
            'slug' => Str::slug('Cooking'),
        ],
        [
            'icon' => 'assets/images/services/electric_work.svg',
            'name' => 'Electric Work',
            'slug' => Str::slug('Electric Work'),
        ],
        [
            'icon' => 'assets/images/services/laundry.svg',
            'name' => 'Laundry',
            'slug' => Str::slug('Laundry'),
        ],
        [
            'icon' => 'assets/images/services/logistics.svg',
            'name' => 'Logistics',
            'slug' => Str::slug('Logistics'),
        ],
        [
            'icon' => 'assets/images/services/painting.svg',
            'name' => 'Painting',
            'slug' => Str::slug('Painting'),
        ],
        [
            'icon' => 'assets/images/services/plumbing.svg',
            'name' => 'Plumbing',
            'slug' => Str::slug('Plumbing'),
        ],
    ];



        DB::table('services')->insert($services);
    }
}
