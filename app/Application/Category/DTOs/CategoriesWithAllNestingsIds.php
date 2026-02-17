<?php

namespace App\Application\Category\DTOs;

use Illuminate\Http\Request;

class CategoriesWithAllNestingsIds
{
    public function __construct(
        public ?int $category_id,
        public ?int $subcategory_id,
        public ?int $nested_subcategory_id,
    ){}

    public static function fromRequest(Request $request): self
    {
        $data = $request->validated();
        return new self(
            category_id: $data['category_id'] ?? null,
            subcategory_id: $data['subcategory_id'] ?? null,
            nested_subcategory_id: $data['nested_subcategory_id'] ?? null,
        );
    }
}

