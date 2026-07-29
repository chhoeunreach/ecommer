<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Sellable phone configuration templates kept separate from inventory.
 */
class PhoneVariant extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'phone_model_id',
        'color',
        'storage',
        'ram',
        'sku_template',
        'barcode_template',
        'cost_price',
        'selling_price',
        'currency',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Get the owning phone model.
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(PhoneModel::class, 'phone_model_id');
    }

    /**
     * Get images tied to this variant.
     */
    public function images(): HasMany
    {
        return $this->hasMany(PhoneImage::class);
    }
}
