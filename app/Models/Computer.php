<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CartCompatible;

class Computer extends Model
{
    use HasFactory, CartCompatible;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'thumbnail_img',
        'gallery',
        'status',
        'brand_id',
        'discount',
        'discount_type',
        'discount_start_date',
        'discount_end_date',
        'tags',
        'has_warranty',
        'warranty_id',
        'meta_title',
        'meta_description',
        'meta_img',
        'colors',
        'choice_options',
        'attributes',
        'is_variant',
        'sku',
        'stock'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function warranty()
    {
        return $this->belongsTo(Warranty::class);
    }

    public function stocks()
    {
        return $this->hasMany(ComputerStock::class);
    }

    public function computer_variants()
    {
        return $this->hasMany(ComputerVariant::class, 'computer_id');
    }

    public function variants()
    {
        return $this->hasMany(ComputerVariant::class, 'computer_id');
    }
}
