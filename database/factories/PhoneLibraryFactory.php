<?php

use App\Models\PhoneBrand;
use App\Models\PhoneModel;
use App\Models\PhoneSpecification;
use App\Models\PhoneVariant;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(PhoneBrand::class, function (Faker $faker) {
    $name = $faker->unique()->company;

    return [
        'name' => $name,
        'slug' => Str::slug($name),
        'is_active' => true,
    ];
});

$factory->define(PhoneModel::class, function (Faker $faker) {
    $name = $faker->unique()->words(3, true);

    return [
        'phone_brand_id' => factory(PhoneBrand::class),
        'model_name' => $name,
        'marketing_name' => $name,
        'slug' => Str::slug($name),
        'year_released' => 2024,
        'product_type' => 'mobile_phone',
        'category' => 'Smartphones',
        'status' => 'active',
    ];
});

$factory->define(PhoneSpecification::class, function () {
    return [
        'phone_model_id' => factory(PhoneModel::class),
        'display_size' => '6.7 inches',
        'chipset' => 'Test Chipset',
        'has_5g' => true,
    ];
});

$factory->define(PhoneVariant::class, function () {
    return [
        'phone_model_id' => factory(PhoneModel::class),
        'color' => 'Black',
        'storage' => '256GB',
        'sku_template' => 'TEST-256-BLK',
        'barcode_template' => 'TEST-256-BLK',
        'currency' => 'USD',
        'is_active' => true,
    ];
});
