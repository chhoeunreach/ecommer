<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('computers', 'sku')) {
            Schema::table('computers', function (Blueprint $table) {
                $table->string('sku')->nullable()->after('name');
            });
        }

        if (!Schema::hasColumn('computers', 'stock')) {
            Schema::table('computers', function (Blueprint $table) {
                $table->integer('stock')->default(0)->after('price');
            });
        }

        if (!Schema::hasTable('computer_variants')) {
            Schema::create('computer_variants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('computer_id')->constrained('computers')->onDelete('cascade');
                $table->string('storage');
                $table->string('display')->nullable();
                $table->string('ram')->nullable();
                $table->string('cpu')->nullable();
                $table->string('chip')->nullable();
                $table->decimal('price', 15, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('computer_variants');

        Schema::table('computers', function (Blueprint $table) {
            if (Schema::hasColumn('computers', 'sku')) {
                $table->dropColumn('sku');
            }
            if (Schema::hasColumn('computers', 'stock')) {
                $table->dropColumn('stock');
            }
        });
    }
};
