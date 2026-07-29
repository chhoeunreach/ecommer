<?php

namespace App\Observers;

use App\Events\PhoneModelUpdated;
use App\Models\PhoneModel;

/**
 * Observer for phone model library changes.
 */
class PhoneModelObserver
{
    /**
     * Handle saved phone models.
     */
    public function saved(PhoneModel $phoneModel): void
    {
        PhoneModelUpdated::dispatch($phoneModel);
    }
}
