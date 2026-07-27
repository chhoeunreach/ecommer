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
        Schema::create('phone_library_update_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('source')->nullable();
            $table->string('status')->default('pending')->index();
            $table->unsignedInteger('brands_count')->default(0);
            $table->unsignedInteger('models_count')->default(0);
            $table->unsignedInteger('variants_count')->default(0);
            $table->unsignedInteger('images_count')->default(0);
            $table->text('message')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phone_library_update_logs');
    }
};
