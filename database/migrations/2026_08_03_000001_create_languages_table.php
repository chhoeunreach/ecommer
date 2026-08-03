<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('languages')) {
            Schema::create('languages', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 100);
                $table->string('code', 100);
                $table->string('app_lang_code')->nullable()->default('en');
                $table->integer('rtl')->default(0);
                $table->boolean('status')->default(true);
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });
        }

        $now = now();

        $languages = [
            [
                'name' => 'English',
                'code' => 'en',
                'app_lang_code' => 'en',
                'rtl' => 0,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Khmer',
                'code' => 'km',
                'app_lang_code' => 'km',
                'rtl' => 0,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($languages as $language) {
            if (! DB::table('languages')->where('code', $language['code'])->exists()) {
                DB::table('languages')->insert($language);
            }
        }
    }

    public function down(): void
    {
        // This table predates Laravel migrations in existing installations.
        // Keep it intact when rolling back to avoid deleting user translations.
    }
};
