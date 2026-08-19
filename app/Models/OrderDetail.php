<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class OrderDetail extends Model
{
    use PreventDemoModeChanges;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function computer()
    {
        return $this->belongsTo(Computer::class, 'product_id');
    }

    public function accessory()
    {
        return $this->belongsTo(Accessory::class, 'product_id');
    }

    // Type-aware resolver: use this instead of ->product when the order line
    // may hold a Computer or Accessory, not just a Product.
    public function getItemAttribute()
    {
        return match ($this->product_type ?? 'product') {
            'computer' => $this->computer,
            'accessory' => $this->accessory,
            default => $this->product,
        };
    }

    public function pickup_point()
    {
        return $this->belongsTo(PickupPoint::class);
    }

    public function refund_request()
    {
        return $this->hasOne(RefundRequest::class);
    }

    public function affiliate_log()
    {
        return $this->hasMany(AffiliateLog::class);
    }
}
