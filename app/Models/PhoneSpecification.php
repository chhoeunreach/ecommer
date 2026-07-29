<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Complete technical specifications for a phone model.
 */
class PhoneSpecification extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'phone_model_id',
        'display_size',
        'display_resolution',
        'refresh_rate',
        'brightness',
        'display_protection',
        'chipset',
        'cpu',
        'gpu',
        'ram',
        'storage',
        'rear_cameras',
        'front_camera',
        'video_recording',
        'battery_capacity',
        'charging_speed',
        'wireless_charging',
        'reverse_charging',
        'has_5g',
        'wifi',
        'bluetooth',
        'nfc',
        'usb_type',
        'operating_system',
        'dimensions',
        'weight',
        'sim_type',
        'water_resistance',
        'color_options',
        'warranty',
        'extra_specs',
    ];

    protected $casts = [
        'rear_cameras' => 'array',
        'wireless_charging' => 'boolean',
        'reverse_charging' => 'boolean',
        'has_5g' => 'boolean',
        'nfc' => 'boolean',
        'color_options' => 'array',
        'extra_specs' => 'array',
    ];

    /**
     * Get the owning phone model.
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(PhoneModel::class, 'phone_model_id');
    }
}
