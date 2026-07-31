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
        if (Schema::hasTable('phone_specifications')) {
            return;
        }

        Schema::create('phone_specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('phone_model_id')->unique()->constrained('phone_models')->cascadeOnDelete();
            $table->string('display_size')->nullable()->index();
            $table->string('display_resolution')->nullable();
            $table->string('refresh_rate')->nullable();
            $table->string('brightness')->nullable();
            $table->string('display_protection')->nullable();
            $table->string('chipset')->nullable()->index();
            $table->string('cpu')->nullable();
            $table->string('gpu')->nullable();
            $table->string('ram')->nullable()->index();
            $table->string('storage')->nullable()->index();
            $table->json('rear_cameras')->nullable();
            $table->string('front_camera')->nullable();
            $table->string('video_recording')->nullable();
            $table->string('battery_capacity')->nullable();
            $table->string('charging_speed')->nullable();
            $table->boolean('wireless_charging')->default(false)->index();
            $table->boolean('reverse_charging')->default(false);
            $table->boolean('has_5g')->default(false)->index();
            $table->string('wifi')->nullable();
            $table->string('bluetooth')->nullable();
            $table->boolean('nfc')->default(false)->index();
            $table->string('usb_type')->nullable();
            $table->string('operating_system')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('weight')->nullable();
            $table->string('sim_type')->nullable();
            $table->string('water_resistance')->nullable();
            $table->json('color_options')->nullable();
            $table->string('warranty')->nullable();
            $table->json('extra_specs')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phone_specifications');
    }
};
