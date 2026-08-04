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
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->string('country')->nullable()->after('sku');
            $table->string('condition')->nullable()->after('country');
            $table->string('storage')->nullable()->after('condition');
            $table->string('code')->nullable()->after('storage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->dropColumn(['country', 'condition', 'storage', 'code']);
        });
    }
};
