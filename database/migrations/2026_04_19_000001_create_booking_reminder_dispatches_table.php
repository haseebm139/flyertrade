<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_reminder_dispatches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recipient_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('notification_type', 64);
            $table->string('interval_key', 8);
            $table->dateTime('slot_starts_at');
            $table->dateTime('fire_at');
            $table->string('status', 16)->default('pending');
            $table->text('failure_reason')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'fire_at']);
            $table->unique(
                ['booking_id', 'recipient_user_id', 'notification_type', 'interval_key', 'slot_starts_at'],
                'booking_reminder_dispatches_unique_slot'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_reminder_dispatches');
    }
};
