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
            $table->enum('type', [
                'booking_created',
                'booking_confirmed',
                'booking_cancelled',
                'booking_completed',
                'payment_success',
                'payment_failed',
                'review_received',
                'message_received',
                'offer_received',
                'system_announcement'
            ]);
            $table->string('title');
            $table->text('message');
            $table->enum('recipient_type', ['admin', 'customer', 'provider', 'all'])->default('customer');
            $table->morphs('notifiable'); // notifiable_type, notifiable_id (polymorphic)
            $table->json('data')->nullable(); // Additional data (booking_id, amount, etc.)
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'read_at']);
            $table->index(['recipient_type', 'created_at']);
            $table->index('type');
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
