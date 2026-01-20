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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role_id')->default('customer')->change();
            $table->string('user_type')->default('customer')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role_id', ['customer', 'provider', 'admin', 'multi'])->default('customer')->change();
            $table->enum('user_type', ['customer', 'provider', 'admin', 'multi'])->default('customer')->change();
        });
    }
};
