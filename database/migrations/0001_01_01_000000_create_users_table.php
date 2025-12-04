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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('avatar')->nullable()->default('assets/images/avatar/default.png');
            $table->string('cover_photo')->nullable();
            // 🔹 Additional fields for your app
            $table->string('phone')->nullable();
            $table->enum('role_id', ['customer', 'provider', 'admin','multi'])->default('customer');
            $table->enum('user_type', ['customer', 'provider', 'admin','multi'])->default('customer');
             
             

            $table->enum ('is_verified', ['pending', 'verified', 'declined'])->default('pending');
            $table->enum ('status', ['active', 'inactive'])->default('active');
            // 🔹 Location fields
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();

            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->string('google_id')->nullable();
            $table->string('facebook_id')->nullable();
            $table->string('apple_id')->nullable();

            $table->integer('otp')->nullable();
            $table->boolean('is_guest')->default(false);

            $table->string('fcm_token')->nullable();
            $table->boolean('is_booking_notification')->default(true);
            $table->boolean('is_promo_option_notification')->default(false);
            $table->string('referral_code')->unique()->nullable();

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
