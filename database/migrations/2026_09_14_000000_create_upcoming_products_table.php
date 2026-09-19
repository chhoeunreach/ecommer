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
        Schema::create('upcoming_products', function (Blueprint $table) {
            $table->id();
            // pre_order = customers can reserve now, coming_soon = teaser + "notify me"
            $table->string('type', 20)->default('pre_order')->index();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('short_description', 500)->nullable();
            $table->text('description')->nullable();
            $table->string('thumbnail_img')->nullable();
            $table->text('gallery')->nullable();
            $table->integer('brand_id')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->decimal('deposit_amount', 15, 2)->nullable();
            $table->dateTime('release_date')->nullable();
            $table->dateTime('preorder_end_date')->nullable();
            $table->string('badge_text', 50)->nullable();
            $table->string('external_link')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upcoming_products');
    }
};
