<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validate phone image create and update requests.
 */
class PhoneImageRequest extends FormRequest
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
            'phone_variant_id' => ['nullable', 'exists:phone_variants,id'],
            'type' => ['required', 'string', 'max:255'],
            'path' => ['nullable', 'string', 'max:255'],
            'source_url' => ['nullable', 'url', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_primary' => ['nullable', 'boolean'],
        ];
    }
}
