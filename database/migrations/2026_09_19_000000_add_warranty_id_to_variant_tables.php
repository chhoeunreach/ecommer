<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Each variant option (product stock row / computer variant) can carry its own warranty.
     */
    public function up(): void
    {
        foreach (['product_stocks', 'computer_variants'] as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'warranty_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->unsignedBigInteger('warranty_id')->nullable();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['product_stocks', 'computer_variants'] as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'warranty_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('warranty_id');
                });
            }
        }
    }
};
