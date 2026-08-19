<?php

namespace App\Models;

use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{

    protected $guarded = [];
    protected $fillable = ['address_id','price','tax','shipping_cost','discount','product_referral_code','coupon_code','coupon_applied','quantity','user_id','temp_user_id','owner_id','product_id','product_type','variation'];

    public function user()
    {
        return $this->belongsTo(User::class);
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

    // Type-aware resolver: use this instead of ->product when the cart row
    // may hold a Computer or Accessory, not just a Product.
    public function getItemAttribute()
    {
        return match ($this->product_type) {
            'computer' => $this->computer,
            'accessory' => $this->accessory,
            default => $this->product,
        };
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
