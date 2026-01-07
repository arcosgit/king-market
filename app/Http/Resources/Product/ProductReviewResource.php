<?php

namespace App\Http\Resources\Product;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_name' => $this->user->name,
            'review' => $this->review,
            'created_at' => Carbon::parse($this->created_at, 'Europe/Moscow')->format('d.m.Y'),
            'rating' => str_repeat('⭐', (int) $this->rating),
        ];
    }
}
