<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_run_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamp('ran_at');
            $table->string('source', 64)->default('schedule:run');
            $table->timestamps();

            $table->index('ran_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_run_logs');
    }
};
