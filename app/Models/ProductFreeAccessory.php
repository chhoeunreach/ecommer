<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Model;

class ProductFreeAccessory extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function translations()
    {
        return $this->hasMany(ProductFreeAccessoryTranslation::class);
    }

    public function getTranslation($field, $lang = false)
    {
        $lang = $lang ?: App::getLocale();
        $translation = $this->translations->firstWhere('lang', $lang);

        return $translation?->{$field} ?: $this->{$field};
    }
}
