<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('free_accessory_enabled')->default(false)->after('featured');
            $table->string('free_accessory_title')->nullable()->after('free_accessory_enabled');
            $table->text('free_accessory_description')->nullable()->after('free_accessory_title');
            $table->unsignedBigInteger('free_accessory_image')->nullable()->after('free_accessory_description');
        });

        Schema::table('product_translations', function (Blueprint $table) {
            $table->string('free_accessory_title')->nullable()->after('description');
            $table->text('free_accessory_description')->nullable()->after('free_accessory_title');
        });
    }

    public function down(): void
    {
        Schema::table('product_translations', function (Blueprint $table) {
            $table->dropColumn(['free_accessory_title', 'free_accessory_description']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'free_accessory_enabled',
                'free_accessory_title',
                'free_accessory_description',
                'free_accessory_image',
            ]);
        });
    }
};
