<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosOrderExport extends Model
{
    protected $fillable = [
        'order_id',
        'pos_transaction_id',
        'pos_customer_id',
        'status',
        'message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
