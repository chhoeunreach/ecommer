<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComputerVariant extends Model
{
    protected $table = 'computer_variants';

    protected $fillable = [
        'computer_id',
        'storage',
        'display',
        'ram',
        'cpu',
        'chip',
        'color',
        'price',
        'stock',
        'warranty_id',
    ];

    public function warranty()
    {
        return $this->belongsTo(Warranty::class);
    }

    public function computer()
    {
        return $this->belongsTo(Computer::class, 'computer_id');
    }
}
