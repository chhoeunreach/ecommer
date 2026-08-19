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
        Schema::table('computers', function (Blueprint $table) {
            $table->text('colors')->nullable();
            $table->text('choice_options')->nullable();
            $table->text('attributes')->nullable();
            $table->boolean('is_variant')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('computers', function (Blueprint $table) {
            $table->dropColumn(['colors', 'choice_options', 'attributes', 'is_variant']);
        });
    }
};
