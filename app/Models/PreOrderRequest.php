<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreOrderRequest extends Model
{
    use HasFactory;

    const STATUSES = ['pending', 'confirmed', 'completed', 'cancelled'];

    protected $fillable = [
        'upcoming_product_id',
        'user_id',
        'type',
        'name',
        'phone',
        'email',
        'quantity',
        'note',
        'status',
    ];

    public function upcomingProduct()
    {
        return $this->belongsTo(UpcomingProduct::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
