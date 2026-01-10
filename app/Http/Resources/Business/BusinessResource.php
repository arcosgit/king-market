<?php

namespace App\Http\Resources\Business;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusinessResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,
            'name' => $this->name ?? null,
            'products_quantity' => $this->products_count,
            'sales' => $this->total_sold_quantity,
            'average_rating' => $this->total_rating_reviews['average_rating'],
            'quantity_reviews' => $this->total_rating_reviews['quantity_reviews']
        ];
    }
}
