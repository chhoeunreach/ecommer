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
        Schema::create('computer_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('computer_id');
            $table->string('variant')->nullable();
            $table->string('sku')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->integer('qty')->default(0);
            $table->string('image')->nullable();
            $table->timestamps();

            $table->index('computer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('computer_stocks');
    }
};
