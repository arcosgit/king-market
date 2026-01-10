<?php
namespace App\Domain\Product\Repositories;

use App\Domain\Product\Entities\Product;

interface ProductRepositoryInterface
{
    public function store(Product $product, int $businessId): int;
    public function update(Product $product): void;
    public function storeCategories(int $productId, array $categories): void;
    public function updateCategories(int $productId, array $categories): void;
    public function storeCharacteristics(int $productId, array $characteristics): void;
    public function deleteCharacteristics(int $productId): void;
    public function storeImages(int $productId, array $images): void;
    public function updateImages(int $productId, array $imageIds): void;
    public function getPricesByIds(array $productIds): array;
}
