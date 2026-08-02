<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('languages')) {
            Schema::create('languages', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique();
                $table->string('app_lang_code')->default('en');
                $table->tinyInteger('rtl')->default(0);
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('languages')) {
            Schema::table('languages', function (Blueprint $table) {
                if (!Schema::hasColumn('languages', 'app_lang_code')) {
                    $table->string('app_lang_code')->default('en')->after('code');
                }
                if (!Schema::hasColumn('languages', 'rtl')) {
                    $table->tinyInteger('rtl')->default(0)->after('app_lang_code');
                }
                if (!Schema::hasColumn('languages', 'status')) {
                    $table->tinyInteger('status')->default(1)->after('rtl');
                }
            });
        }

        if (Schema::hasTable('languages') && DB::table('languages')->where('code', 'en')->doesntExist()) {
            DB::table('languages')->insert([
                'name' => 'English',
                'code' => 'en',
                'app_lang_code' => 'en',
                'rtl' => 0,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('languages');
    }
};
