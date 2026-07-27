<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validate phone variant create and update requests.
 */
class PhoneVariantRequest extends FormRequest
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
            'color' => ['required', 'string', 'max:255'],
            'storage' => ['required', 'string', 'max:255'],
            'ram' => ['nullable', 'string', 'max:255'],
            'sku_template' => ['nullable', 'string', 'max:255'],
            'barcode_template' => ['nullable', 'string', 'max:255'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
