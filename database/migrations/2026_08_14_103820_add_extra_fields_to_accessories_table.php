<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accessories', function (Blueprint $table) {
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
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accessories', function (Blueprint $table) {
            $table->dropColumn([
                'brand_id',
                'discount',
                'discount_type',
                'discount_start_date',
                'discount_end_date',
                'tags',
                'has_warranty',
                'warranty_id',
                'meta_title',
                'meta_description',
                'meta_img'
            ]);
        });
    }
};
