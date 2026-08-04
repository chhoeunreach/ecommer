<?php

namespace MehediIitdu\CoreComponentRepository;

use App\Models\Addon;
use Cache;

class CoreComponentRepository
{
    public static function instantiateShopRepository()
    {
        // License activation is intentionally disabled for this local build.
    }

    public static function initializeCache()
    {
        foreach (Addon::all() as $addon) {
            Cache::rememberForever($addon->unique_identifier.'-purchased', function () {
                return 'yes';
            });
        }
    }

    public static function finalizeCache($addon)
    {
        return null;
    }
}
