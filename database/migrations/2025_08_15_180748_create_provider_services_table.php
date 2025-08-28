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
        Schema::create('provider_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // provider
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->foreignId('provider_profile_id')->constrained()->onDelete('cascade');
            $table->boolean('is_primary')->default(false);
            $table->boolean('show_certificate')->default(true);
            $table->string('title')->nullable();
            $table->longText('about')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedInteger('staff_count')->nullable();
            $table->decimal('rate_min', 10, 2)->nullable();
            $table->decimal('rate_max', 10, 2)->nullable();
            $table->unique(['user_id', 'service_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_services');
    }
};
