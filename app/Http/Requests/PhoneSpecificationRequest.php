<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validate phone specification requests.
 */
class PhoneSpecificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get validation rules.
     */
    public function rules(): array
    {
        return [
            'phone_model_id' => ['required', 'exists:phone_models,id'],
            'display_size' => ['nullable', 'string', 'max:255'],
            'display_resolution' => ['nullable', 'string', 'max:255'],
            'refresh_rate' => ['nullable', 'string', 'max:255'],
            'brightness' => ['nullable', 'string', 'max:255'],
            'display_protection' => ['nullable', 'string', 'max:255'],
            'chipset' => ['nullable', 'string', 'max:255'],
            'cpu' => ['nullable', 'string', 'max:255'],
            'gpu' => ['nullable', 'string', 'max:255'],
            'ram' => ['nullable', 'string', 'max:255'],
            'storage' => ['nullable', 'string', 'max:255'],
            'rear_cameras' => ['nullable', 'array'],
            'front_camera' => ['nullable', 'string', 'max:255'],
            'video_recording' => ['nullable', 'string', 'max:255'],
            'battery_capacity' => ['nullable', 'string', 'max:255'],
            'charging_speed' => ['nullable', 'string', 'max:255'],
            'wireless_charging' => ['nullable', 'boolean'],
            'reverse_charging' => ['nullable', 'boolean'],
            'has_5g' => ['nullable', 'boolean'],
            'wifi' => ['nullable', 'string', 'max:255'],
            'bluetooth' => ['nullable', 'string', 'max:255'],
            'nfc' => ['nullable', 'boolean'],
            'usb_type' => ['nullable', 'string', 'max:255'],
            'operating_system' => ['nullable', 'string', 'max:255'],
            'dimensions' => ['nullable', 'string', 'max:255'],
            'weight' => ['nullable', 'string', 'max:255'],
            'sim_type' => ['nullable', 'string', 'max:255'],
            'water_resistance' => ['nullable', 'string', 'max:255'],
            'color_options' => ['nullable', 'array'],
            'warranty' => ['nullable', 'string', 'max:255'],
            'extra_specs' => ['nullable', 'array'],
        ];
    }
}
