<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $mobile = DB::table('categories')->where('name', 'Mobile')->where('parent_id', 0)->first();

        if (!$mobile) {
            return;
        }

        $attributeNames = ['Storage', 'Code country', 'Condition'];
        $attributeIds = DB::table('attributes')->whereIn('name', $attributeNames)->pluck('id');

        foreach ($attributeIds as $attributeId) {
            $exists = DB::table('attribute_category')
                ->where('category_id', $mobile->id)
                ->where('attribute_id', $attributeId)
                ->exists();

            if (!$exists) {
                DB::table('attribute_category')->insert([
                    'category_id' => $mobile->id,
                    'attribute_id' => $attributeId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $mobile = DB::table('categories')->where('name', 'Mobile')->where('parent_id', 0)->first();

        if (!$mobile) {
            return;
        }

        $attributeNames = ['Storage', 'Code country', 'Condition'];
        $attributeIds = DB::table('attributes')->whereIn('name', $attributeNames)->pluck('id');

        DB::table('attribute_category')
            ->where('category_id', $mobile->id)
            ->whereIn('attribute_id', $attributeIds)
            ->delete();
    }
};
