<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('business_settings')) {
            return;
        }

        $settings = [
            'base_color' => '#814D99',
            'base_hov_color' => '#5E3870',
            'secondary_base_color' => '#17171f',
            'secondary_base_hov_color' => '#5D5D62',
            'use_image_watermark' => 'off',
            'image_watermark_type' => 'image',
            'watermark_image' => null,
            'watermark_text' => 'Watermark Text Here',
            'watermark_text_size' => '20',
            'watermark_text_color' => '#e1e1e1',
            'watermark_position' => 'top-left',
        ];

        foreach ($settings as $type => $value) {
            DB::table('business_settings')->updateOrInsert(
                ['type' => $type, 'lang' => null],
                [
                    'value' => $value,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }

    public function down()
    {
        if (!Schema::hasTable('business_settings')) {
            return;
        }

        $settings = [
            'base_color' => '#d43533',
            'base_hov_color' => '#9d1b1a',
            'secondary_base_color' => '#ffc519',
            'secondary_base_hov_color' => '#dbaa17',
            'use_image_watermark' => 'off',
            'image_watermark_type' => 'image',
            'watermark_image' => null,
            'watermark_text' => 'Watermark Text Here',
            'watermark_text_size' => '20',
            'watermark_text_color' => '#e1e1e1',
            'watermark_position' => 'top-left',
        ];

        foreach ($settings as $type => $value) {
            DB::table('business_settings')->updateOrInsert(
                ['type' => $type, 'lang' => null],
                [
                    'value' => $value,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
};
