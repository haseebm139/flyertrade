<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\ProviderProfile;
use App\Models\ProviderService;
use App\Models\ProviderWorkingHour;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TestingFlowSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting Testing Flow Seeder...');

        // 1. Roles ensure
        $roles = ['admin', 'provider', 'customer', 'multi'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // 2. Admin User
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'user_type' => 'admin',
                'role_id' => 'admin',
                'email_verified_at' => now(),
            ]
        )->assignRole('admin');

        // 3. Services (Categories)
        if (Service::count() === 0) {
            $this->call(ServiceSeeder::class);
        }
        $services = Service::all();

        // // 4. Customers
        // $customers = collect();
        // for ($i = 1; $i <= 5; $i++) {
        //     $customer = User::firstOrCreate(
        //         ['email' => "customer{$i}@test.com"],
        //         [
        //             'name' => "Customer {$i}",
        //             'password' => Hash::make('password'),
        //             'user_type' => 'customer',
        //             'role_id' => 'customer',
        //             'email_verified_at' => now(),
        //             'phone' => '123456789' . $i,
        //             'address' => "Customer Address {$i}, Dubai",
        //         ]
        //     );
        //     $customer->assignRole('customer');
        //     $customers->push($customer);
        // }

        // // 5. Providers
        // $providers = collect();
        // for ($i = 1; $i <= 5; $i++) {
        //     $provider = User::firstOrCreate(
        //         ['email' => "provider{$i}@test.com"],
        //         [
        //             'name' => "Provider {$i}",
        //             'password' => Hash::make('password'),
        //             'user_type' => 'provider',
        //             'role_id' => 'provider',
        //             'email_verified_at' => now(),
        //             'phone' => '987654321' . $i,
        //             'address' => "Provider Work Space {$i}, Dubai",
        //         ]
        //     );
        //     $provider->assignRole('provider');
            
        //     // Create Profile
        //     $profile = ProviderProfile::firstOrCreate(
        //         ['user_id' => $provider->id],
        //         [
        //             'availability_status' => 'available',
        //             'bio' => "Professional service provider {$i} with 5 years of experience.",
        //         ]
        //     );

        //     // Assign random services
        //     $randomServices = $services->random(rand(1, 3));
        //     foreach ($randomServices as $service) {
        //         ProviderService::firstOrCreate(
        //             ['user_id' => $provider->id, 'service_id' => $service->id],
        //             [
        //                 'provider_profile_id' => $profile->id,
        //                 'rate_min' => rand(20, 50),
        //                 'rate_max' => rand(60, 150),
        //                 'description' => "I offer high quality {$service->name} services.",
        //             ]
        //         );
        //     }

        //     // Seed Working Hours
        //     if ($provider->workingHours()->count() === 0) {
        //         ProviderWorkingHour::seedDefaultHours($provider->id, $profile->id);
        //     }

        //     $providers->push($provider);
        // }

        // // 6. Bookings Flow
        // $bookingStatuses = ['awaiting_provider', 'confirmed', 'in_progress', 'completed', 'cancelled'];
        
        // foreach ($customers as $customer) {
        //     // Create 3 bookings for each customer
        //     for ($j = 0; $j < 3; $j++) {
        //         $provider = $providers->random();
        //         $providerService = ProviderService::where('user_id', $provider->id)->first();
        //         $status = $bookingStatuses[array_rand($bookingStatuses)];
                
        //         $bookingRef = 'FT-' . strtoupper(Str::random(8));
                
        //         $workingMinutes = rand(60, 240);
        //         $totalPrice = rand(100, 500);

        //         // Calculate service charges dynamically based on admin settings
        //         $percentage = (float) \App\Models\Setting::get('service_charge_percentage', 25); 
        //         $serviceCharges = ($totalPrice * $percentage) / 100;

        //         $booking = Booking::create([
        //             'booking_ref' => $bookingRef,
        //             'customer_id' => $customer->id,
        //             'provider_id' => $provider->id,
        //             'service_id' => $providerService->service_id,
        //             'provider_service_id' => $providerService->id,
        //             'booking_address' => $customer->address,
        //             'booking_description' => "Need assistance with {$providerService->service->name}.",
        //             'status' => $status,
        //             'booking_type' => 'hourly',
        //             'booking_working_minutes' => $workingMinutes,
        //             'total_price' => $totalPrice,
        //             'service_charges' => $serviceCharges,
        //             'created_at' => now()->subDays(rand(1, 30)),
        //         ]);

        //         // If completed, create transaction and review
        //         if ($status === 'completed') {
        //             $booking->update(['completed_at' => $booking->created_at->addHours(2)]);

        //             // Create Transaction
        //             Transaction::create([
        //                 'booking_id' => $booking->id,
        //                 'customer_id' => $customer->id,
        //                 'provider_id' => $provider->id,
        //                 'transaction_ref' => Transaction::generateRef(),
        //                 'type' => 'payment',
        //                 'status' => 'succeeded',
        //                 'amount' => $booking->total_price,
        //                 'service_charges' => $booking->service_charges,
        //                 'net_amount' => $booking->total_price - $booking->service_charges,
        //                 'currency' => 'AED',
        //                 'completed_at' => $booking->completed_at,
        //             ]);

        //             // Create Review (Customer -> Provider)
        //             Review::create([
        //                 'booking_id' => $booking->id,
        //                 'sender_id' => $customer->id,
        //                 'receiver_id' => $provider->id,
        //                 'service_id' => $booking->service_id,
        //                 'rating' => rand(4, 5),
        //                 'review' => "Great service by {$provider->name}! Very professional.",
        //                 'status' => 'published',
        //             ]);

        //             // Optional: Create Review (Provider -> Customer)
        //             if (rand(0, 1)) {
        //                 Review::create([
        //                     'booking_id' => $booking->id,
        //                     'sender_id' => $provider->id,
        //                     'receiver_id' => $customer->id,
        //                     'service_id' => $booking->service_id,
        //                     'rating' => rand(4, 5),
        //                     'review' => "Polite and helpful customer. Recommended!",
        //                     'status' => 'published',
        //                 ]);
        //             }
        //         }
        //     }
        // }

        $this->command->info('Testing Flow Seeder completed successfully!');
    }
}
