<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('pos_api_settings')) {
            Schema::create('pos_api_settings', function (Blueprint $table) {
                $table->id();
                $table->string('pos_base_url')->default('http://localhost');
                $table->text('api_token')->nullable();
                $table->string('shop_domain')->nullable()->default('127.0.0.1:8001');
                $table->boolean('is_active')->default(true);
                $table->timestamp('last_sync_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('pos_api_settings');
    }
};
