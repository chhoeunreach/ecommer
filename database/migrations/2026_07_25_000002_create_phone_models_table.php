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
        if (Schema::hasTable('phone_models')) {
            return;
        }

        Schema::create('phone_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('phone_brand_id')->constrained('phone_brands')->cascadeOnDelete();
            $table->string('model_name');
            $table->string('marketing_name')->nullable();
            $table->string('slug')->unique();
            $table->unsignedSmallInteger('year_released')->nullable()->index();
            $table->string('model_number')->nullable()->index();
            $table->string('product_type')->default('mobile_phone')->index();
            $table->string('category')->nullable()->index();
            $table->string('status')->default('active')->index();
            $table->text('description')->nullable();
            $table->string('source_url')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->json('metadata')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['phone_brand_id', 'model_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phone_models');
    }
};
