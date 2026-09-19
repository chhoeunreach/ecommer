<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpadStock extends Model
{
    protected $fillable = [
        'ipad_id',
        'variant',
        'sku',
        'price',
        'qty',
        'image',
    ];

    public function ipad()
    {
        return $this->belongsTo(Ipad::class);
    }
}
