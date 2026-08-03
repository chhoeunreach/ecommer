<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_free_accessories', function (Blueprint $table) {
            $table->id();
            // This legacy application's products.id is a signed INT.
            $table->integer('product_id');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        Schema::create('product_free_accessory_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_free_accessory_id');
            $table->string('lang', 20);
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['product_free_accessory_id', 'lang'], 'pfa_translation_unique');
            $table->foreign('product_free_accessory_id', 'pfa_translation_accessory_fk')
                ->references('id')->on('product_free_accessories')->cascadeOnDelete();
        });

        // Preserve values saved by the original single-accessory implementation.
        DB::table('products')
            ->where('free_accessory_enabled', 1)
            ->orderBy('id')
            ->chunkById(100, function ($products) {
                foreach ($products as $product) {
                    $accessoryId = DB::table('product_free_accessories')->insertGetId([
                        'product_id' => $product->id,
                        'title' => $product->free_accessory_title,
                        'description' => $product->free_accessory_description,
                        'image' => $product->free_accessory_image,
                        'sort_order' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $translations = DB::table('product_translations')
                        ->where('product_id', $product->id)
                        ->where(function ($query) {
                            $query->whereNotNull('free_accessory_title')
                                ->orWhereNotNull('free_accessory_description');
                        })->get();

                    foreach ($translations as $translation) {
                        DB::table('product_free_accessory_translations')->insert([
                            'product_free_accessory_id' => $accessoryId,
                            'lang' => $translation->lang,
                            'title' => $translation->free_accessory_title,
                            'description' => $translation->free_accessory_description,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_free_accessory_translations');
        Schema::dropIfExists('product_free_accessories');
    }
};
