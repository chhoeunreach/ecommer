<?php

namespace Tests\Feature;

use App\Http\Controllers\BusinessSettingsController;
use App\Models\BusinessSetting;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HomepageSelectionTest extends TestCase
{
    private $originalConnection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->originalConnection = config('database.default');
        config([
            'database.default' => 'homepage_selection_test',
            'database.connections.homepage_selection_test' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
        ]);

        DB::purge('homepage_selection_test');
        Schema::connection('homepage_selection_test')->create('business_settings', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->text('value')->nullable();
            $table->string('lang')->nullable();
            $table->timestamps();
        });
        Schema::connection('homepage_selection_test')->create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('lang');
            $table->text('lang_key');
            $table->text('lang_value')->nullable();
            $table->timestamps();
        });
        Schema::connection('homepage_selection_test')->create('app_translations', function (Blueprint $table) {
            $table->id();
            $table->string('lang');
            $table->text('lang_key');
            $table->text('lang_value')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Cache::forget('business_settings');
        DB::purge('homepage_selection_test');
        config(['database.default' => $this->originalConnection]);

        parent::tearDown();
    }

    public function test_selecting_megamart_invalidates_the_cached_homepage(): void
    {
        $setting = new BusinessSetting();
        $setting->type = 'homepage_select';
        $setting->value = 'thecore';
        $setting->save();

        Cache::put('business_settings', collect([$setting]), 86400);
        $this->assertSame('thecore', get_setting('homepage_select'));

        Artisan::shouldReceive('call')
            ->once()
            ->with('cache:clear')
            ->andReturn(0);

        $request = Request::create('/admin/business-settings/update', 'POST', [
            'types' => ['homepage_select'],
            'homepage_select' => 'megamart',
        ]);

        app(BusinessSettingsController::class)->update($request);

        $this->assertSame('megamart', BusinessSetting::where('type', 'homepage_select')->value('value'));
        $this->assertSame('megamart', get_setting('homepage_select'));
    }
}
