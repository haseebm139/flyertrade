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
        Schema::table('bookings', function (Blueprint $table) {
            $table->boolean('late_action_taken')->default(false)->after('cancelled_at');
            $table->enum('late_action_type', ['wait', 'reschedule', 'escalate'])->nullable()->after('late_action_taken');
            $table->timestamp('late_action_at')->nullable()->after('late_action_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['late_action_taken', 'late_action_type', 'late_action_at']);
        });
    }
};
