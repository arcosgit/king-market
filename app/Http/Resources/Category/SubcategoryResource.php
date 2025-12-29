<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubcategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'subcategory_id' => $this->id,
            'subcategory_name' => $this->name,
            'nested_categories' => NestedCategoryResource::collection($this->nestedCategories)->resolve()
        ];
    }
}
