<?php

namespace App\Events;

use App\Models\PhoneModel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event fired when phone model library data changes.
 */
class PhoneModelUpdated
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public PhoneModel $phoneModel)
    {
    }
}
