<?php
namespace App\Domain\Product\Repositories;

use App\Domain\Product\Entities\Product;

interface ProductRepositoryInterface
{
    public function store(Product $product, int $businessId): int;
    public function storeCategories(int $productId, array $categories): void;
    public function storeCharacteristics(int $productId, array $characteristics): void;
    public function storeImages(int $productId, array $images): void;
}
