<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('custom_labels') && !Schema::hasColumn('custom_labels', 'status')) {
            Schema::table('custom_labels', function (Blueprint $table) {
                $table->tinyInteger('status')->default(1)->after('seller_access');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('custom_labels') && Schema::hasColumn('custom_labels', 'status')) {
            Schema::table('custom_labels', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
