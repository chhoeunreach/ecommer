<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetShopDatabase extends Command
{
    protected $signature = 'db:reset-shop';
    protected $description = 'Wipe the database, import shop.sql, insert missing migration records, and run migrations';

    public function handle()
    {
        $this->info('1. Wiping the database...');
        $this->call('db:wipe');

        $sqlPath = base_path('shop.sql');
        if (!file_exists($sqlPath)) {
            $this->error('shop.sql not found in the root directory.');
            return 1;
        }

        $this->info('2. Importing shop.sql (this may take a moment)...');
        $sql = file_get_contents($sqlPath);
        DB::unprepared($sql);

        $this->info('3. Fixing migration records for vendor packages...');
        $missingMigrations = [
            ['migration' => '2019_12_14_000001_create_personal_access_tokens_table', 'batch' => 1],
            ['migration' => '2021_06_07_000000_create_payku_transactions_table', 'batch' => 1],
            ['migration' => '2021_06_07_000001_create_payku_payments_table', 'batch' => 1],
            ['migration' => '2021_12_15_000000_add_new_columns_to_tables', 'batch' => 1],
            ['migration' => '2022_06_29_075906_create_product_queries_table', 'batch' => 1],
        ];

        foreach ($missingMigrations as $record) {
            if (!DB::table('migrations')->where('migration', $record['migration'])->exists()) {
                DB::table('migrations')->insert($record);
            }
        }

        $this->info('4. Running newer migrations...');
        $this->call('migrate');

        $this->info('Database reset and fully migrated successfully!');
        return 0;
    }
}
