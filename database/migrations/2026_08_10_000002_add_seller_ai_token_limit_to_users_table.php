<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        $hasTokenLimit = Schema::hasColumn('users', 'seller_monthly_token_limit');
        $hasSetupDate = Schema::hasColumn('users', 'seller_monthly_token_limit_setup_date');

        if (!$hasTokenLimit || !$hasSetupDate) {
            Schema::table('users', function (Blueprint $table) use ($hasTokenLimit, $hasSetupDate) {
                if (!$hasTokenLimit) {
                    $table->unsignedInteger('seller_monthly_token_limit')
                        ->default(0)
                        ->after('remember_token');
                }

                if (!$hasSetupDate) {
                    $table->timestamp('seller_monthly_token_limit_setup_date')
                        ->nullable()
                        ->after('seller_monthly_token_limit');
                }
            });
        }

        $configuredLimit = 0;

        if (Schema::hasTable('business_settings')) {
            $configuredLimit = max(0, (int) DB::table('business_settings')
                ->where('type', 'seller_monthly_token_limit')
                ->value('value'));
        }

        DB::table('users')
            ->whereNull('seller_monthly_token_limit_setup_date')
            ->update([
                'seller_monthly_token_limit' => $configuredLimit,
                'seller_monthly_token_limit_setup_date' => now(),
            ]);
    }

    public function down(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        $columns = array_values(array_filter([
            Schema::hasColumn('users', 'seller_monthly_token_limit')
                ? 'seller_monthly_token_limit'
                : null,
            Schema::hasColumn('users', 'seller_monthly_token_limit_setup_date')
                ? 'seller_monthly_token_limit_setup_date'
                : null,
        ]));

        if ($columns !== []) {
            Schema::table('users', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
};
