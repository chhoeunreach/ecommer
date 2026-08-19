<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComputerStock extends Model
{
    protected $fillable = [
        'computer_id',
        'variant',
        'sku',
        'price',
        'qty',
        'image',
        'storage',
        'display',
        'ram',
        'cpu',
        'chip',
        'is_manual',
    ];

    public function computer()
    {
        return $this->belongsTo(Computer::class);
    }
}
