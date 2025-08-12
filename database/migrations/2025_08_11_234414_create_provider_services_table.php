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
            $table->boolean('is_primary')->default(false);

            $table->string('title')->nullable();            // service title
            $table->text('description')->nullable();        // description
            $table->unsignedInteger('staff_count')->nullable();
            $table->json('service_photos')->nullable();     // store array of file paths
            $table->string('service_video')->nullable();    // single video path or url
            $table->decimal('rate_min', 8, 2)->nullable();
            $table->decimal('rate_max', 8, 2)->nullable();
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
