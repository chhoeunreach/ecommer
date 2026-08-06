<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auction_product_bids', function (Blueprint $table) {
            $table->id();
            // This legacy application's products.id is a signed INT and users.id is unsigned INT.
            $table->integer('product_id');
            $table->unsignedInteger('user_id');
            $table->double('amount')->default(0);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auction_product_bids');
    }
};
