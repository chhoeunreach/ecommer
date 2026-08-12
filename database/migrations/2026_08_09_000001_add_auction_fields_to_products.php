<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->double('starting_bid', 20, 2)->default(0.00)->after('auction_product');
            $table->integer('auction_start_date')->nullable()->after('starting_bid');
            $table->integer('auction_end_date')->nullable()->after('auction_start_date');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['starting_bid', 'auction_start_date', 'auction_end_date']);
        });
    }
};
