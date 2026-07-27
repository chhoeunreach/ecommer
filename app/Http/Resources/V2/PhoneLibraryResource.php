<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * API representation of a phone model.
 */
class PhoneLibraryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'brand' => $this->whenLoaded('brand', fn () => [
                'id' => $this->brand->id,
                'name' => $this->brand->name,
                'slug' => $this->brand->slug,
            ]),
            'model_name' => $this->model_name,
            'marketing_name' => $this->marketing_name,
            'slug' => $this->slug,
            'year_released' => $this->year_released,
            'model_number' => $this->model_number,
            'product_type' => $this->product_type,
            'category' => $this->category,
            'status' => $this->status,
            'description' => $this->description,
            'specification' => new PhoneSpecificationResource($this->whenLoaded('specification')),
            'variants' => PhoneVariantResource::collection($this->whenLoaded('variants')),
            'images' => PhoneImageResource::collection($this->whenLoaded('images')),
        ];
    }
}
