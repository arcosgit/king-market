<?php

namespace App\Application\Product\DTOs;

use Illuminate\Http\Request;

class EditProductData
{
    public function __construct(
        public string $name,
        public string $description,
        public float $price,
        public array $characteristics,
        public array $categories,
        public array $images,

    ){}
    public static function fromRequest(Request $request)
    {
        $data = $request->validated();
        return new self(
            name: $data['name'],
            description: $data['description'],
            price: $data['price'],
            characteristics: $data['characteristics'],
            categories: $data['categories'],
            images: $data['images'],
        );
    }
}
