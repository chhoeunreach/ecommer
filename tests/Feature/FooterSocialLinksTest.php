<?php

namespace Tests\Feature;

use App\Models\BusinessSetting;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class FooterSocialLinksTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['cache.default' => 'array']);
    }

    public function test_it_uses_legacy_social_links_before_repeatable_links_are_saved(): void
    {
        Cache::put('business_settings', collect([
            $this->setting('facebook_link', 'https://facebook.com/shop'),
            $this->setting('youtube_link', 'https://youtube.com/shop'),
            $this->setting('instagram_link', ''),
        ]));

        $this->assertSame([
            ['platform' => 'facebook', 'url' => 'https://facebook.com/shop', 'icon' => ''],
            ['platform' => 'youtube', 'url' => 'https://youtube.com/shop', 'icon' => ''],
        ], footer_social_links());
    }

    public function test_it_returns_all_saved_repeatable_social_links_and_ignores_blank_rows(): void
    {
        Cache::put('business_settings', collect([
            $this->setting('social_link_platforms', '["","telegram","tiktok","website"]'),
            $this->setting('social_link_urls', '["","https://t.me/shop","https://tiktok.com/@shop","https://shop.test"]'),
            $this->setting('social_link_icons', '["","","42",""]'),
        ]));

        $this->assertSame([
            ['platform' => 'telegram', 'url' => 'https://t.me/shop', 'icon' => ''],
            ['platform' => 'tiktok', 'url' => 'https://tiktok.com/@shop', 'icon' => '42'],
            ['platform' => 'website', 'url' => 'https://shop.test', 'icon' => ''],
        ], footer_social_links());
    }

    public function test_the_uploader_file_base_url_has_a_trailing_slash(): void
    {
        $originalHost = $_SERVER['HTTP_HOST'] ?? null;
        $originalScriptName = $_SERVER['SCRIPT_NAME'] ?? null;
        $_SERVER['HTTP_HOST'] = 'localhost:8000';
        $_SERVER['SCRIPT_NAME'] = '/index.php';

        try {
            $this->assertSame('//localhost:8000/public/', getFileBaseURL());
        } finally {
            if ($originalHost === null) {
                unset($_SERVER['HTTP_HOST']);
            } else {
                $_SERVER['HTTP_HOST'] = $originalHost;
            }
            if ($originalScriptName === null) {
                unset($_SERVER['SCRIPT_NAME']);
            } else {
                $_SERVER['SCRIPT_NAME'] = $originalScriptName;
            }
        }
    }

    private function setting(string $type, string $value): BusinessSetting
    {
        $setting = new BusinessSetting();
        $setting->setRawAttributes(compact('type', 'value'));

        return $setting;
    }
}
