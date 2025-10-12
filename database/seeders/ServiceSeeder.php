<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class ServiceSeeder extends Seeder
{

    // Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
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
            'description' => 'General residential cleaning services, including deep cleaning, bathroom cleaning, kitchen cleaning, and post-renovation cleanups.',
        ],                    
        [
            'icon' => 'assets/images/services/carpenter.png',
            'name' => 'Carpenter',
            'slug' => Str::slug('Carpenter'),
            'description' => 'Woodwork services including furniture repairs, cabinet making, door and window installations, and custom carpentry projects.',
        
        ],
        [
            'icon' => 'assets/images/services/laundry.png',
            'name' => 'Laundry',
            'slug' => Str::slug('Laundry'),
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim a',
        
        ],
        [
            'icon' => 'assets/images/services/painting.png',
            'name' => 'Painting',
            'slug' => Str::slug('Painting'),
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim a',
        
        ],
        [
            'icon' => 'assets/images/services/logistics.png',
            'name' => 'Logistics',
            'slug' => Str::slug('Logistics'),
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim a',
        
        ],
        [
            'icon' => 'assets/images/services/cooking.png',
            'name' => 'Cooking',
            'slug' => Str::slug('Cooking'),
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim a',
        
        ],
        [
            'icon' => 'assets/images/services/electric_work.png',
            'name' => 'Electric Work',
            'slug' => Str::slug('Electric Work'),
            'description' => 'Electrical installation, repairs, wiring, lighting fixtures, appliance setup, and troubleshooting electrical faults',
        
        ], 
        [
            'icon' => 'assets/images/services/plumbing.png',
            'name' => 'Plumbing',
            'slug' => Str::slug('Plumbing'),
            'description' => 'Installation, maintenance, and repair of plumbing systems, including leak repairs, pipe fitting, unclogging drains, and bathroom/kitc..',
        
        ],
        [
            'icon' => 'assets/images/services/beauty.png',
            'name' => 'Beauty',
            'slug' => Str::slug('Beauty'),
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim a',
        
        ],
        [
            'icon' => 'assets/images/services/technician.png',
            'name' => 'Technician',
            'slug' => Str::slug('Technician'),
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim a',
        
        ],
        [
            'icon' => 'assets/images/services/ac_repair.png',
            'name' => 'AC repair',
            'slug' => Str::slug('AC repair'),
            'description' => 'Air conditioning unit installation, repairs, cleaning, gas refilling, and regular servicing to improve cooling efficiency.',
        
        ],
        [
            'icon' => 'assets/images/services/baking.png',
            'name' => 'Baking',
            'slug' => Str::slug('Baking'),
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim a',
        
        ],
        [
            'icon' => 'assets/images/services/gardener.png',
            'name' => 'Gardener',
            'slug' => Str::slug('Gardener'),
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim a',
        
        ],
        [
            'icon' => 'assets/images/services/man_saloon.png',
            'name' => 'Man\'s saloon',
            'slug' => Str::slug('Man\'s saloon'),
            'description' => 'Electrical installation, repairs, wiring, lighting fixtures, appliance setup, and troubleshooting electrical faults',
        
        ],
    ];



        DB::table('services')->insert($services);
    }
}
