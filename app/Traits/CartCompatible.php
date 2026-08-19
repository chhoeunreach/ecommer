<?php

namespace App\Traits;

use App\Models\ProductTax;
use App\Models\User;

/**
 * Lets a simple admin-owned catalog model (Computer, Accessory — no seller,
 * no translations, no auction/wholesale/digital support) flow through the
 * same Cart/Checkout/Order code paths written for Product, by answering the
 * same properties/relations Product exposes with sane fixed defaults.
 */
trait CartCompatible
{
    protected static $cartCompatibleAdminUserId = null;

    public function taxes()
    {
        return $this->hasMany(ProductTax::class, 'product_id', 'id')->whereRaw('1 = 0');
    }

    public function getTranslation($field = '', $lang = false)
    {
        return $this->$field;
    }

    public function getAuctionProductAttribute()
    {
        return 0;
    }

    public function getWholesaleProductAttribute()
    {
        return 0;
    }

    public function getMinQtyAttribute()
    {
        return 1;
    }

    public function getDigitalAttribute()
    {
        return 0;
    }

    public function getWeightAttribute()
    {
        return 0;
    }

    // No admin UI exists yet to configure per-item shipping cost for these
    // simpler catalog types, so default to free shipping rather than crash
    // or silently charge an undefined amount.
    public function getShippingCostAttribute()
    {
        return 0;
    }

    public function getIsQuantityMultipliedAttribute()
    {
        return 0;
    }

    public function getCashOnDeliveryAttribute()
    {
        return 1;
    }

    public function getGstRateAttribute()
    {
        return 0;
    }

    public function getEarnPointAttribute()
    {
        return 0;
    }

    // Treated as an admin catalog item (no seller): shipping/order-splitting
    // and seller-stat code branch on added_by/user_id, so this must resolve
    // to the site's admin account rather than null.
    public function getAddedByAttribute()
    {
        return 'admin';
    }

    public function getUserIdAttribute()
    {
        if (static::$cartCompatibleAdminUserId === null) {
            static::$cartCompatibleAdminUserId = User::where('user_type', 'admin')->value('id') ?? 0;
        }

        return static::$cartCompatibleAdminUserId;
    }
}
