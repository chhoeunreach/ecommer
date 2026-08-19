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
        Schema::table('computer_stocks', function (Blueprint $table) {
            $table->string('storage')->nullable();
            $table->string('display')->nullable();
            $table->string('ram')->nullable();
            $table->string('cpu')->nullable();
            $table->string('chip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('computer_stocks', function (Blueprint $table) {
            $table->dropColumn(['storage', 'display', 'ram', 'cpu', 'chip']);
        });
    }
};
