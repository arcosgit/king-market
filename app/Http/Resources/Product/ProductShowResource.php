<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'brand_id' => $this->business_id,
            'brand_name' => $this->business->name,
            'images' => ProductImageResource::collection($this->images->where('hide', false))->resolve(),
            'characteristics' => ProductCharacteristicResource::collection($this->characteristics)->resolve(),
            'rating_average' => $this->reviews()->count() > 0 ? (int) $this->reviews()->sum('rating') / (int) $this->reviews()->count() : null,
            'reviews_count' => $this->reviews()->count() != 0 ? $this->reviews()->count() . ' ' . trans_choice(__('product.reviews_quantity'), $this->reviews()->count()) : null,
            'is_favorite' => $this->favorite,
        ];
    }
}
