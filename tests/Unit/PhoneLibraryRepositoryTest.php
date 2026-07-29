<?php

namespace Tests\Unit;

use App\Models\PhoneBrand;
use App\Models\PhoneModel;
use App\Models\PhoneSpecification;
use App\Repositories\PhoneLibraryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhoneLibraryRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_filters_by_5g_specification()
    {
        $brand = factory(PhoneBrand::class)->create();
        $model = factory(PhoneModel::class)->create(['phone_brand_id' => $brand->id]);
        factory(PhoneSpecification::class)->create(['phone_model_id' => $model->id, 'has_5g' => true]);

        $results = app(PhoneLibraryRepository::class)->searchModels(['has_5g' => 1], 10);

        $this->assertSame(1, $results->total());
    }
}
