<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpcomingProduct extends Model
{
    use HasFactory;

    const TYPE_PRE_ORDER = 'pre_order';
    const TYPE_COMING_SOON = 'coming_soon';

    protected $fillable = [
        'type',
        'name',
        'slug',
        'short_description',
        'description',
        'thumbnail_img',
        'gallery',
        'brand_id',
        'price',
        'deposit_amount',
        'release_date',
        'preorder_end_date',
        'badge_text',
        'external_link',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'release_date' => 'datetime',
        'preorder_end_date' => 'datetime',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function requests()
    {
        return $this->hasMany(PreOrderRequest::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function isPreOrder()
    {
        return $this->type === self::TYPE_PRE_ORDER;
    }

    // A pre-order stays open until its closing date passes (no date = always open).
    public function isPreOrderOpen()
    {
        return $this->isPreOrder()
            && ($this->preorder_end_date === null || $this->preorder_end_date->isFuture());
    }

    // Date the frontend counts down to: pre-order closing date first, then the release date.
    public function countdownDate()
    {
        if ($this->isPreOrderOpen() && $this->preorder_end_date) {
            return $this->preorder_end_date;
        }

        return $this->release_date && $this->release_date->isFuture() ? $this->release_date : null;
    }
}
