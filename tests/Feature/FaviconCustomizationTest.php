<?php

namespace Tests\Feature;

use App\Http\Controllers\FaviconController;
use App\Models\BusinessSetting;
use App\Models\Upload;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FaviconCustomizationTest extends TestCase
{
    private $originalConnection;
    private $originalCacheStore;

    protected function setUp(): void
    {
        parent::setUp();

        $this->originalConnection = config('database.default');
        $this->originalCacheStore = config('cache.default');
        config([
            'cache.default' => 'array',
            'database.default' => 'favicon_customization_test',
            'database.connections.favicon_customization_test' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
        ]);

        DB::purge('favicon_customization_test');
        Schema::connection('favicon_customization_test')->create('business_settings', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->text('value')->nullable();
            $table->string('lang')->nullable();
            $table->timestamps();
        });
        Schema::connection('favicon_customization_test')->create('uploads', function (Blueprint $table) {
            $table->id();
            $table->string('file_original_name')->nullable();
            $table->string('file_name')->nullable();
            $table->string('external_link')->nullable();
            $table->string('extension')->nullable();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Cache::forget('business_settings');
        DB::purge('favicon_customization_test');
        config([
            'cache.default' => $this->originalCacheStore,
            'database.default' => $this->originalConnection,
        ]);

        parent::tearDown();
    }

    public function test_the_favicon_endpoint_redirects_to_the_selected_site_icon(): void
    {
        $upload = new Upload();
        $upload->file_name = 'uploads/all/shop-icon.png';
        $upload->external_link = 'https://cdn.example.com/shop-icon.png';
        $upload->save();

        $setting = new BusinessSetting();
        $setting->type = 'site_icon';
        $setting->value = (string) $upload->id;
        $setting->save();

        Cache::forget('business_settings');

        $response = app(FaviconController::class)();

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame('https://cdn.example.com/shop-icon.png', $response->headers->get('Location'));
        $this->assertTrue($response->headers->getCacheControlDirective('public'));
        $this->assertSame('3600', $response->headers->getCacheControlDirective('max-age'));
    }

    public function test_the_favicon_endpoint_returns_not_found_without_a_selected_icon(): void
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        app(FaviconController::class)();
    }
}
