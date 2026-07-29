<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Local or source image references for a phone model or variant.
 */
class PhoneImage extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'phone_model_id',
        'phone_variant_id',
        'type',
        'path',
        'source_url',
        'hash',
        'sort_order',
        'is_primary',
        'metadata',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_primary' => 'boolean',
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
     * Get the owning variant when the image is variant-specific.
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(PhoneVariant::class, 'phone_variant_id');
    }
}
