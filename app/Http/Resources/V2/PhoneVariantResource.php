<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * API representation of a phone variant template.
 */
class PhoneVariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'phone_model_id' => $this->phone_model_id,
            'color' => $this->color,
            'storage' => $this->storage,
            'ram' => $this->ram,
            'sku_template' => $this->sku_template,
            'barcode_template' => $this->barcode_template,
            'cost_price' => $this->cost_price,
            'selling_price' => $this->selling_price,
            'currency' => $this->currency,
            'is_active' => $this->is_active,
        ];
    }
}
