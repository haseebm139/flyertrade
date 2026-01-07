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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('provider_id')->constrained('users')->cascadeOnDelete();
            
            // Transaction details
            $table->string('transaction_ref')->unique(); // TXN-20250904-ABC123
            $table->enum('type', ['payment', 'refund', 'payout'])->default('payment');
            $table->enum('status', ['pending', 'processing', 'succeeded', 'failed', 'cancelled', 'refunded'])->default('pending');
            
            // Amount details
            $table->decimal('amount', 10, 2); // Total amount
            $table->decimal('service_charges', 10, 2)->default(0); // Platform fee
            $table->decimal('net_amount', 10, 2); // Amount after service charges
            $table->string('currency', 3)->default('usd');
            
            // Stripe details
            $table->string('stripe_payment_intent_id')->nullable()->index();
            $table->string('stripe_payment_method_id')->nullable();
            $table->string('stripe_charge_id')->nullable();
            $table->string('stripe_customer_id')->nullable();
            
            // Payment status
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            
            // Additional info
            $table->text('failure_reason')->nullable();
            $table->json('metadata')->nullable(); // Additional data
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['customer_id', 'status']);
            $table->index(['provider_id', 'status']);
            $table->index(['booking_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
