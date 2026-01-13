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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type'); // Changed from enum to string for flexibility
            $table->string('icon')->nullable();
            $table->string('category')->nullable(); // 'all', 'reviews', 'bookings', 'transactions', 'admin_actions'
            $table->string('title');
            $table->text('message');
            $table->enum('recipient_type', ['admin', 'customer', 'provider', 'all'])->default('customer');
            $table->string('notifiable_type')->nullable(); // Polymorphic type
            $table->unsignedBigInteger('notifiable_id')->nullable(); // Polymorphic ID
            $table->json('data')->nullable(); // Additional data (booking_id, amount, etc.)
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'read_at']);
            $table->index(['recipient_type', 'created_at']);
            $table->index('type');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
