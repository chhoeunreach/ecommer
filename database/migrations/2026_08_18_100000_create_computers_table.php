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
        Schema::create('computers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->string('thumbnail_img')->nullable();
            $table->text('gallery')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();

            $table->integer('brand_id')->nullable();
            $table->double('discount', 8, 2)->nullable();
            $table->string('discount_type')->nullable();
            $table->integer('discount_start_date')->nullable();
            $table->integer('discount_end_date')->nullable();
            $table->text('tags')->nullable();
            $table->boolean('has_warranty')->default(0);
            $table->integer('warranty_id')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_img')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('computers');
    }
};
