<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('permissions')) {
            return;
        }

        DB::table('permissions')->updateOrInsert(
            ['name' => 'view_customer_orders', 'guard_name' => 'web'],
            ['section' => 'sale', 'created_at' => now(), 'updated_at' => now()]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('permissions')) {
            DB::table('permissions')->where('name', 'view_customer_orders')->delete();
        }
    }
};
