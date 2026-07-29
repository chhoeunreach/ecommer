<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('pos_sync_mappings')) {
            Schema::create('pos_sync_mappings', function (Blueprint $table) {
                $table->id();
                $table->string('entity_type', 30);
                $table->string('pos_id', 100);
                $table->unsignedBigInteger('ecommerce_id');
                $table->timestamps();

                $table->unique(['entity_type', 'pos_id']);
                $table->index(['entity_type', 'ecommerce_id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('pos_sync_mappings');
    }
};
