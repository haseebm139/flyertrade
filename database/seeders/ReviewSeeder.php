<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Booking;
use App\Models\User;
use App\Models\Service;
use App\Models\ProviderService;
use App\Models\ProviderProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample review texts
        $reviewTexts = [
            'Excellent service! Very professional and completed the work on time. Highly recommended!',
            'Great experience overall. The provider was punctual and did a fantastic job.',
            'Good service, but could be improved in some areas. Overall satisfied.',
            'Outstanding work quality. Exceeded my expectations. Will definitely book again.',
            'Very professional and friendly. The work was done perfectly. Thank you!',
            'Good service provider. Completed the task efficiently and cleaned up afterwards.',
            'Satisfactory service. Met the basic requirements but nothing exceptional.',
            'Amazing work! Very detail-oriented and professional. Worth every penny.',
            'Decent service. Provider was on time and completed the work as expected.',
            'Excellent communication and service quality. Highly satisfied with the results.',
            'Great job! The provider was knowledgeable and completed everything perfectly.',
            'Good experience. Professional approach and timely completion of work.',
            'Very happy with the service. Provider was courteous and efficient.',
            'Satisfactory work. Met expectations and completed on schedule.',
            'Outstanding service! Would definitely recommend to others.',
        ];

        // Get completed bookings that don't have reviews yet
        $bookings = Booking::where('status', 'completed')
            ->whereDoesntHave('review')
            ->with(['customer', 'provider', 'service'])
            ->get();

        if ($bookings->isEmpty()) {
            $this->command->warn('No completed bookings found without reviews. Checking for any existing bookings...');
            
            // Get any bookings that exist (any status)
            $bookings = Booking::with(['customer', 'provider', 'service'])
                ->whereDoesntHave('review')
                ->limit(20)
                ->get();
        }

        // If still no bookings, create some sample bookings
        if ($bookings->isEmpty()) {
            $this->command->info('No bookings found. Creating sample bookings...');
            $bookings = $this->createSampleBookings();
            
            if ($bookings->isEmpty()) {
                $this->command->error('Could not create sample bookings. Please ensure you have users, services, and provider profiles seeded first.');
                return;
            }
        }

        $statuses = ['pending', 'published', 'published', 'published', 'published']; // More published reviews
        $ratings = [5, 5, 4, 4, 4, 3, 3, 5, 4, 5]; // Mix of ratings, mostly positive

        $createdCount = 0;

        foreach ($bookings as $booking) {
            // Skip if booking doesn't have required relationships
            if (!$booking->customer || !$booking->provider || !$booking->service) {
                continue;
            }

            // Random rating (1-5)
            $rating = $ratings[array_rand($ratings)];
            
            // Random review text
            $reviewText = $reviewTexts[array_rand($reviewTexts)];
            
            // Random status (mostly published)
            $status = $statuses[array_rand($statuses)];

            try {
                Review::create([
                    'booking_id' => $booking->id,
                    'sender_id' => $booking->customer_id, // Customer who wrote the review
                    'receiver_id' => $booking->provider_id, // Provider being reviewed
                    'service_id' => $booking->service_id,
                    'rating' => $rating,
                    'review' => $reviewText,
                    'status' => $status,
                ]);

                $createdCount++;
            } catch (\Exception $e) {
                // Skip if review already exists for this booking (unique constraint)
                $this->command->warn("Skipping booking {$booking->id}: " . $e->getMessage());
            }
        }

        $this->command->info("Successfully created {$createdCount} reviews.");
    }

    /**
     * Create sample bookings for testing reviews
     */
    private function createSampleBookings()
    {
        // Get customers and providers
        $customers = User::whereIn('user_type', ['customer', 'multi'])->get();
        $providers = User::whereIn('user_type', ['provider', 'multi'])->get();
        $services = Service::all();

        if ($customers->isEmpty() || $providers->isEmpty() || $services->isEmpty()) {
            $this->command->warn('Missing required data: customers, providers, or services.');
            return collect([]);
        }

        $bookings = collect([]);
        $addresses = [
            '123 Main Street, New York, NY 10001',
            '456 Oak Avenue, Los Angeles, CA 90001',
            '789 Pine Road, Chicago, IL 60601',
            '321 Elm Street, Houston, TX 77001',
            '654 Maple Drive, Phoenix, AZ 85001',
        ];

        // Create 10 sample bookings
        for ($i = 0; $i < 10; $i++) {
            $customer = $customers->random();
            $provider = $providers->random();
            $service = $services->random();

            // Check if provider has a profile
            $providerProfile = ProviderProfile::where('user_id', $provider->id)->first();
            
            if (!$providerProfile) {
                // Create a basic provider profile if it doesn't exist
                $providerProfile = ProviderProfile::create([
                    'user_id' => $provider->id,
                    'availability_status' => 'available',
                ]);
            }

            // Check if provider service exists
            $providerService = ProviderService::where('user_id', $provider->id)
                ->where('service_id', $service->id)
                ->first();

            if (!$providerService) {
                // Create a provider service if it doesn't exist
                $providerService = ProviderService::create([
                    'user_id' => $provider->id,
                    'service_id' => $service->id,
                    'provider_profile_id' => $providerProfile->id,
                    'rate_min' => rand(20, 50),
                    'rate_max' => rand(50, 100),
                ]);
            }

            // Generate unique booking reference
            $bookingRef = 'FT-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            while (Booking::where('booking_ref', $bookingRef)->exists()) {
                $bookingRef = 'FT-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }

            try {
                $booking = Booking::create([
                    'booking_ref' => $bookingRef,
                    'customer_id' => $customer->id,
                    'provider_id' => $provider->id,
                    'service_id' => $service->id,
                    'provider_service_id' => $providerService->id,
                    'booking_address' => $addresses[array_rand($addresses)],
                    'booking_description' => 'Sample booking for review seeder',
                    'status' => 'completed',
                    'booking_type' => 'hourly',
                    'booking_working_minutes' => rand(60, 480), // 1 to 8 hours
                    'total_price' => rand(50, 500),
                    'service_charges' => rand(5, 50),
                    'paid_at' => now()->subDays(rand(1, 30)),
                ]);

                $bookings->push($booking);
            } catch (\Exception $e) {
                $this->command->warn("Failed to create booking: " . $e->getMessage());
            }
        }

        $this->command->info("Created {$bookings->count()} sample bookings.");
        
        // Load relationships for the created bookings
        return Booking::whereIn('id', $bookings->pluck('id'))
            ->with(['customer', 'provider', 'service'])
            ->get();
    }
}

