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
            $table->enum('reschedule_initiated_by', ['customer', 'provider'])->nullable()->after('status');
            $table->enum('reschedule_response', ['accepted', 'rejected'])->nullable()->after('reschedule_initiated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['reschedule_initiated_by', 'reschedule_response']);
        });
    }
};
