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
        if (Schema::hasTable('phone_variants')) {
            return;
        }

        Schema::create('phone_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('phone_model_id')->constrained('phone_models')->cascadeOnDelete();
            $table->string('color')->index();
            $table->string('storage')->index();
            $table->string('ram')->nullable()->index();
            $table->string('sku_template')->nullable()->unique();
            $table->string('barcode_template')->nullable()->unique();
            $table->decimal('cost_price', 20, 2)->default(0);
            $table->decimal('selling_price', 20, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->boolean('is_active')->default(true)->index();
            $table->json('metadata')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['phone_model_id', 'color', 'storage', 'ram'], 'phone_variant_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phone_variants');
    }
};
