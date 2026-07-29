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
        if (Schema::hasTable('phone_images')) {
            return;
        }

        Schema::create('phone_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('phone_model_id')->constrained('phone_models')->cascadeOnDelete();
            $table->foreignId('phone_variant_id')->nullable()->constrained('phone_variants')->nullOnDelete();
            $table->string('type')->default('main')->index();
            $table->string('path');
            $table->string('source_url')->nullable();
            $table->string('hash')->nullable()->unique();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_primary')->default(false)->index();
            $table->json('metadata')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phone_images');
    }
};
