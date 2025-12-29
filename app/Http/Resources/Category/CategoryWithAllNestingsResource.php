<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryWithAllNestingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'category_id' => $this->id,
            'category_name' => $this->name,
            'subcategories' => SubcategoryResource::collection($this->subcategories)->resolve()
        ];
    }
}
