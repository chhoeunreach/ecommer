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

        $now = now();
        foreach ([
            'phone_library.view',
            'phone_library.create',
            'phone_library.edit',
            'phone_library.delete',
            'phone_library.import',
            'phone_library.update',
        ] as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission, 'guard_name' => 'web'],
                ['section' => 'product', 'created_at' => $now, 'updated_at' => $now]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('permissions')) {
            DB::table('permissions')->whereIn('name', [
                'phone_library.view',
                'phone_library.create',
                'phone_library.edit',
                'phone_library.delete',
                'phone_library.import',
                'phone_library.update',
            ])->delete();
        }
    }
};
