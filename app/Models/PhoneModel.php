<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Official model-level phone data, independent from store inventory.
 */
class PhoneModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'phone_brand_id',
        'model_name',
        'marketing_name',
        'slug',
        'year_released',
        'model_number',
        'product_type',
        'category',
        'status',
        'description',
        'source_url',
        'last_synced_at',
        'metadata',
    ];

    protected $casts = [
        'year_released' => 'integer',
        'last_synced_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the phone brand.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(PhoneBrand::class, 'phone_brand_id');
    }

    /**
     * Get the specification record.
     */
    public function specification(): HasOne
    {
        return $this->hasOne(PhoneSpecification::class);
    }

    /**
     * Get the available variants.
     */
    public function variants(): HasMany
    {
        return $this->hasMany(PhoneVariant::class);
    }

    /**
     * Get model images.
     */
    public function images(): HasMany
    {
        return $this->hasMany(PhoneImage::class);
    }

    /**
     * Get the primary image.
     */
    public function primaryImage(): HasOne
    {
        return $this->hasOne(PhoneImage::class)->where('is_primary', true)->orderBy('sort_order');
    }
}
