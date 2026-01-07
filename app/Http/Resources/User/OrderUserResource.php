<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Product\ProductCardResource;
use App\Http\Resources\Product\ProductOrderCardResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $products = $this->products->map(function ($orderProduct) {
            $product = $orderProduct->product;
            $product->quantity = $orderProduct->quantity;
            return $product;
        });
        return [
            'id' => $this->id,
            'total_cost' => $this->total_cost,
            'created_at' => Carbon::parse($this->created_at, 'Europe/Moscow')->format('d.m.Y'),
            'products' => ProductCardResource::collection($products)->resolve(),
        ];
    }
}
