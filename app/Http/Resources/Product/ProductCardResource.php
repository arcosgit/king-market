<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if(!isset($this->id)){
            return [
                'no_product' => true,
            ];
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'img' => url('build/storage/' . $this->image->img),
            'rating_average' => $this->reviews()->count() > 0 ? floor(((int) $this->reviews()->sum('rating') / (int) $this->reviews()->count()) * 10) / 10 : null,
            'reviews_count' => $this->reviews()->count() != 0 ? $this->reviews()->count() . ' ' . trans_choice(__('product.reviews_quantity'), $this->reviews()->count()) : null,
            'quantity' => $this->quantity ?? null,
            'review_text' => $this?->userReview?->review,
            'review_rating' => $this?->userReview?->rating,
            'is_favorite' => $this->favorite,
        ];
    }
}
