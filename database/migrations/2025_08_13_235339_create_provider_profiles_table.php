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
        Schema::create('provider_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Basic info
            $table->text('about_me')->nullable();
            $table->string('profile_photo')->nullable();

            // Location info
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('office_address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Document verification statuses
            $table->enum('id_photo_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('passport_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('work_permit_status', ['pending', 'approved', 'rejected'])->default('pending');

            // Completion tracking
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_profiles');
    }
};
