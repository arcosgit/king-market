<?php

namespace App\Application\Product\DTOs;

use Illuminate\Http\Request;

class BuyProductData
{
    public function __construct(
        public array $products,
    ){}

    public static function fromRequest(Request $request): self
    {
        $data = $request->validated();
        return new self(
            products: $data['products'],
        );
    }
}

