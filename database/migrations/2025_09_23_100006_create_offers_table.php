<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id')->index();
            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('provider_id')->index();
            $table->string('service_type', 191)->index();
            $table->timestamp('time_from')->nullable()->index();
            $table->timestamp('time_to')->nullable()->index();
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'countered', 'bargained', 'accepted', 'declined', 'finalized'])->default('pending')->index();
            $table->unsignedBigInteger('current_revision_id')->nullable()->index();
            $table->timestamp('responded_at')->nullable();
            $table->timestamp('finalized_at')->nullable();
            $table->timestamps();

            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('provider_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};

