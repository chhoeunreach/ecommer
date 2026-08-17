<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $defaultLang = env('DEFAULT_LANGUAGE', 'en');

        // ---- Computer category ----
        $categoryId = DB::table('categories')->insertGetId([
            'parent_id' => 0,
            'level' => 0,
            'name' => 'Computer',
            'order_level' => (int) DB::table('categories')->max('order_level') + 1,
            'commision_rate' => 0,
            'digital' => 0,
            'slug' => 'computer-' . Str::random(5),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('category_translations')->insert([
            'category_id' => $categoryId,
            'name' => 'Computer',
            'lang' => $defaultLang,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ---- Attributes ----
        $attributes = [
            'Display' => ['13.3"', '14"', '15.6"', '16"', '17.3"', '24"', '27"', '32"'],
            'Storage' => ['128GB', '256GB', '512GB', '1TB', '2TB', '4TB'],
            'RAM' => ['4GB', '8GB', '16GB', '32GB', '64GB', '128GB'],
            'CPU' => ['Intel Core i3', 'Intel Core i5', 'Intel Core i7', 'Intel Core i9', 'AMD Ryzen 5', 'AMD Ryzen 7', 'AMD Ryzen 9'],
            'Chip' => ['Apple M1', 'Apple M2', 'Apple M3', 'Intel', 'Qualcomm Snapdragon'],
            'Code country' => ['US', 'UK', 'EU', 'JP', 'AE', 'SA', 'IN', 'CN'],
            'Condition' => ['New', 'Refurbished', 'Used', 'Open Box', 'Like New'],
        ];

        foreach ($attributes as $attributeName => $values) {
            $attributeId = DB::table('attributes')->insertGetId([
                'name' => $attributeName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('attribute_translations')->insert([
                'attribute_id' => $attributeId,
                'name' => $attributeName,
                'lang' => $defaultLang,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($values as $value) {
                DB::table('attribute_values')->insert([
                    'attribute_id' => $attributeId,
                    'value' => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('attribute_category')->insert([
                'category_id' => $categoryId,
                'attribute_id' => $attributeId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $computer = DB::table('categories')->where('name', 'Computer')->where('parent_id', 0)->first();

        $attributeNames = ['Display', 'Storage', 'RAM', 'CPU', 'Chip', 'Code country', 'Condition'];

        $attributeIds = DB::table('attributes')->whereIn('name', $attributeNames)->pluck('id')->toArray();

        if ($computer) {
            DB::table('attribute_category')->where('category_id', $computer->id)->whereIn('attribute_id', $attributeIds)->delete();
            DB::table('category_translations')->where('category_id', $computer->id)->delete();
            DB::table('categories')->where('id', $computer->id)->delete();
        }

        foreach ($attributeIds as $attributeId) {
            DB::table('attribute_translations')->where('attribute_id', $attributeId)->delete();
            DB::table('attribute_values')->where('attribute_id', $attributeId)->delete();
            DB::table('attributes')->where('id', $attributeId)->delete();
        }
    }
};
