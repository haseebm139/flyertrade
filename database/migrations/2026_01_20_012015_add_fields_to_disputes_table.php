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
        Schema::table('disputes', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->after('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->text('message')->after('booking_id');
            $table->string('attachment')->after('message')->nullable();
            $table->string('status')->after('attachment')->default('unresolved'); // resolve , unresolved
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disputes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['booking_id']);
            $table->dropColumn(['user_id', 'booking_id', 'message', 'attachment', 'status']);
        });
    }
};
