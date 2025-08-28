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
        Schema::create('provider_working_hours', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // provider
            $table->foreignId('provider_profile_id')->constrained()->onDelete('cascade');
            $table->enum('day', ['sunday','monday','tuesday','wednesday','thursday','friday','saturday']);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->unique(['user_id','day']); // only one row per day per provider 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_working_hours');
    }
};
