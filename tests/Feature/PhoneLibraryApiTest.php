<?php

namespace Tests\Feature;

use App\Models\PhoneBrand;
use App\Models\PhoneModel;
use App\Models\PhoneSpecification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhoneLibraryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_phone_library_models_can_be_listed()
    {
        config(['app.system_key' => 'test-key']);

        $brand = factory(PhoneBrand::class)->create(['name' => 'Apple', 'slug' => 'apple']);
        $model = factory(PhoneModel::class)->create([
            'phone_brand_id' => $brand->id,
            'model_name' => 'iPhone Test',
            'slug' => 'iphone-test',
        ]);
        factory(PhoneSpecification::class)->create(['phone_model_id' => $model->id, 'has_5g' => true]);

        $response = $this->withHeader('System-Key', 'test-key')
            ->getJson('/api/v2/phone-library?brand=Apple');

        $response->assertOk();
        $response->assertJsonFragment(['model_name' => 'iPhone Test']);
    }
}
