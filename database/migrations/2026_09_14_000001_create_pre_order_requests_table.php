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
        Schema::create('pre_order_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('upcoming_product_id')->index();
            $table->integer('user_id')->nullable();
            // pre_order = reservation, notify = "notify me when available"
            $table->string('type', 20)->default('pre_order');
            $table->string('name');
            $table->string('phone', 50);
            $table->string('email')->nullable();
            $table->integer('quantity')->default(1);
            $table->text('note')->nullable();
            // pending, confirmed, completed, cancelled
            $table->string('status', 20)->default('pending')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_order_requests');
    }
};
