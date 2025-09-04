<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_ref')->unique(); // FT-20250904-ABC123
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('provider_id')->constrained('users')->cascadeOnDelete();

            $table->string('booking_address');
            $table->text('booking_description')->nullable();
            
            $table->enum('status', [
                'awaiting_provider', 'confirmed','in_progress','rejected', 'completed', 'cancelled', 'refunded'
            ])->default('awaiting_provider');        // awaiting_provider, confirmed, in_progress, rejected, completed, cancelled, refunded
            

            $table->unsignedInteger('booking_working_minutes')->default(0);
            $table->decimal('total_price', 10, 2);     // amount customer paid
            $table->decimal('service_charges', 10, 2)->default(0); // platform fee or tax if you need

            // Stripe
            $table->string('stripe_payment_intent_id')->nullable()->index();
            $table->string('stripe_payment_method_id')->nullable();
            $table->timestamp('paid_at')->nullable();

            // provider decision deadline (now + 2 hours)
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();
            $table->index(['provider_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
