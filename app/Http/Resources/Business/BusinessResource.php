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
            'products_quantity' => $this->products_count ?? null,
            'sales' => $this->total_sold_quantity ?? null,
            'average_rating' => $this->total_rating_reviews['average_rating'] ?? null,
            'quantity_reviews' => $this->total_rating_reviews['quantity_reviews'] ?? null
        ];
    }
}
