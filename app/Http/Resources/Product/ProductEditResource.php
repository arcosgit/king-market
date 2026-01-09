<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductEditResource extends JsonResource
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
            'images' => ProductImageAllResource::collection($this->images)->resolve(),
            'characteristics' => ProductCharacteristicResource::collection($this->characteristics)->resolve(),
            'categories' => [
                'category_id' => $this->categories->category_id,
                'subcategory_id' => $this->categories->subcategory_id,
                'nested_subcategory_id' => $this->categories->nested_subcategory_id
            ],
        ];
    }
}
