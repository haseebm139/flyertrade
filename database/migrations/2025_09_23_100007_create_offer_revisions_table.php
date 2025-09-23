<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('offer_revisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offer_id')->index();
            $table->unsignedBigInteger('by_user_id')->index();
            $table->json('cost_items')->nullable();
            $table->json('materials')->nullable();
            $table->decimal('flat_fee', 10, 2)->default(0);
            $table->string('currency', 8)->default('USD');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
            $table->foreign('by_user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('offers', function (Blueprint $table) {
            if (!Schema::hasColumn('offers', 'current_revision_id')) return;
            $table->foreign('current_revision_id')->references('id')->on('offer_revisions')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            if (Schema::hasColumn('offers', 'current_revision_id')) {
                $table->dropForeign(['current_revision_id']);
            }
        });
        Schema::dropIfExists('offer_revisions');
    }
};

