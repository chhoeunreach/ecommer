<?php

namespace App\Repositories;

use App\Models\PhoneBrand;
use App\Models\PhoneModel;
use App\Models\PhoneVariant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Query and persistence operations for the phone library.
 */
class PhoneLibraryRepository
{
    /**
     * Search phone models with eager-loaded library relations.
     */
    public function searchModels(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return $this->baseModelQuery($filters)
            ->latest('phone_models.updated_at')
            ->paginate($perPage)
            ->appends($filters);
    }

    /**
     * Get one phone model with full preview data.
     */
    public function findModel(int $id): PhoneModel
    {
        return PhoneModel::with(['brand', 'specification', 'variants.images', 'images'])
            ->findOrFail($id);
    }

    /**
     * Upsert a complete model payload from seeders or update sources.
     */
    public function upsertModel(array $payload): PhoneModel
    {
        $brand = PhoneBrand::updateOrCreate(
            ['slug' => Str::slug($payload['brand']['name'])],
            [
                'name' => $payload['brand']['name'],
                'country' => Arr::get($payload, 'brand.country'),
                'website' => Arr::get($payload, 'brand.website'),
                'is_active' => true,
            ]
        );

        $model = PhoneModel::updateOrCreate(
            ['slug' => Arr::get($payload, 'slug', Str::slug($brand->name . '-' . $payload['model_name']))],
            [
                'phone_brand_id' => $brand->id,
                'model_name' => $payload['model_name'],
                'marketing_name' => Arr::get($payload, 'marketing_name'),
                'year_released' => Arr::get($payload, 'year_released'),
                'model_number' => Arr::get($payload, 'model_number'),
                'product_type' => Arr::get($payload, 'product_type', 'mobile_phone'),
                'category' => Arr::get($payload, 'category', 'Smartphones'),
                'status' => Arr::get($payload, 'status', 'active'),
                'description' => Arr::get($payload, 'description'),
                'source_url' => Arr::get($payload, 'source_url'),
                'last_synced_at' => now(),
                'metadata' => Arr::get($payload, 'metadata'),
            ]
        );

        $model->specification()->updateOrCreate(
            ['phone_model_id' => $model->id],
            Arr::get($payload, 'specification', [])
        );

        foreach (Arr::get($payload, 'variants', []) as $variant) {
            $model->variants()->updateOrCreate(
                [
                    'color' => $variant['color'],
                    'storage' => $variant['storage'],
                    'ram' => Arr::get($variant, 'ram'),
                ],
                $variant
            );
        }

        return $model->fresh(['brand', 'specification', 'variants', 'images']);
    }

    /**
     * Build the reusable search query.
     */
    private function baseModelQuery(array $filters): Builder
    {
        $query = PhoneModel::query()->with(['brand', 'specification', 'primaryImage'])->withCount('variants');

        $query->when(Arr::get($filters, 'brand'), function (Builder $query, string $brand): void {
            $query->whereHas('brand', fn (Builder $brandQuery) => $brandQuery->where('name', 'like', "%{$brand}%"));
        });

        $query->when(Arr::get($filters, 'model'), function (Builder $query, string $model): void {
            $query->where(function (Builder $inner) use ($model): void {
                $inner->where('model_name', 'like', "%{$model}%")
                    ->orWhere('marketing_name', 'like', "%{$model}%")
                    ->orWhere('model_number', 'like', "%{$model}%");
            });
        });

        $query->when(Arr::get($filters, 'year'), fn (Builder $query, $year) => $query->where('year_released', $year));
        $query->when(Arr::get($filters, 'status'), fn (Builder $query, $status) => $query->where('status', $status));

        $query->when(Arr::get($filters, 'storage'), function (Builder $query, string $storage): void {
            $query->whereHas('variants', fn (Builder $variant) => $variant->where('storage', 'like', "%{$storage}%"));
        });

        $query->when(Arr::get($filters, 'color'), function (Builder $query, string $color): void {
            $query->whereHas('variants', fn (Builder $variant) => $variant->where('color', 'like', "%{$color}%"));
        });

        $query->when(Arr::has($filters, 'has_5g') && $filters['has_5g'] !== '', function (Builder $query) use ($filters): void {
            $query->whereHas('specification', fn (Builder $spec) => $spec->where('has_5g', (bool) $filters['has_5g']));
        });

        $query->when(Arr::get($filters, 'chipset'), function (Builder $query, string $chipset): void {
            $query->whereHas('specification', fn (Builder $spec) => $spec->where('chipset', 'like', "%{$chipset}%"));
        });

        $query->when(Arr::get($filters, 'display_size'), function (Builder $query, string $size): void {
            $query->whereHas('specification', fn (Builder $spec) => $spec->where('display_size', 'like', "%{$size}%"));
        });

        return $query;
    }
}
