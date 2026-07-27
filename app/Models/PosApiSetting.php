<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class PosApiSetting extends Model
{
    protected $fillable = [
        'pos_base_url',
        'api_token',
        'shop_domain',
        'is_active',
        'last_sync_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_sync_at' => 'datetime',
    ];

    public static function current()
    {
        if (!Schema::hasTable('pos_api_settings')) {
            return new static([
                'pos_base_url' => 'http://localhost',
                'shop_domain' => '127.0.0.1:8001',
                'is_active' => true,
            ]);
        }

        return static::query()->firstOrNew([], [
            'pos_base_url' => 'http://localhost',
            'shop_domain' => '127.0.0.1:8001',
            'is_active' => true,
        ]);
    }
}
