<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validate phone model create and update requests.
 */
class PhoneModelRequest extends FormRequest
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
        $modelId = $this->route('model')?->id ?? $this->route('phone_model')?->id;

        return [
            'phone_brand_id' => ['required', 'exists:phone_brands,id'],
            'model_name' => ['required', 'string', 'max:255'],
            'marketing_name' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('phone_models', 'slug')->ignore($modelId)],
            'year_released' => ['nullable', 'integer', 'between:1990,2100'],
            'model_number' => ['nullable', 'string', 'max:255'],
            'product_type' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['active', 'discontinued'])],
            'description' => ['nullable', 'string'],
            'source_url' => ['nullable', 'url', 'max:255'],
        ];
    }
}
