<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Records library sync/update attempts without touching inventory.
 */
class PhoneLibraryUpdateLog extends Model
{
    protected $fillable = [
        'user_id',
        'source',
        'status',
        'brands_count',
        'models_count',
        'variants_count',
        'images_count',
        'message',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    /**
     * Get the user that started the update.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
