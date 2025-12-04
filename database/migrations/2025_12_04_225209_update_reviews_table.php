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
        $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade')->comment('Customer who wrote the review');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade')->comment('Provider being reviewed');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned()->default(1)->comment('Rating from 1 to 5');
            $table->text('review')->nullable()->comment('Review text content');
            $table->enum('status', ['pending', 'published', 'unpublished'])->default('pending');

            // Indexes for better query performance
            $table->index('booking_id');
            $table->index('sender_id');
            $table->index('receiver_id');
            $table->index('service_id');
            $table->index('status');
            
            // Ensure one review per booking
            $table->unique('booking_id');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
