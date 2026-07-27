<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosSyncMapping extends Model
{
    protected $fillable = [
        'entity_type',
        'pos_id',
        'ecommerce_id',
    ];

    public static function ecommerceId(string $entityType, $posId): ?int
    {
        $mapping = static::where('entity_type', $entityType)
            ->where('pos_id', (string) $posId)
            ->first();

        return $mapping ? (int) $mapping->ecommerce_id : null;
    }

    public static function remember(string $entityType, $posId, $ecommerceId): void
    {
        static::updateOrCreate(
            ['entity_type' => $entityType, 'pos_id' => (string) $posId],
            ['ecommerce_id' => $ecommerceId]
        );
    }

    public static function posId(string $entityType, $ecommerceId): ?string
    {
        $mapping = static::where('entity_type', $entityType)
            ->where('ecommerce_id', $ecommerceId)
            ->first();

        return $mapping ? (string) $mapping->pos_id : null;
    }
}
