<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessoryStock extends Model
{
    protected $fillable = [
        'accessory_id',
        'variant',
        'sku',
        'price',
        'qty',
        'image',
    ];

    public function accessory()
    {
        return $this->belongsTo(Accessory::class);
    }
}
