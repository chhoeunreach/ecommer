<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFreeAccessoryTranslation extends Model
{
    protected $guarded = [];

    public function accessory()
    {
        return $this->belongsTo(ProductFreeAccessory::class, 'product_free_accessory_id');
    }
}
