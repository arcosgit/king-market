<?php
namespace App\Infrastructure\Repositories;

use App\Domain\Product\Entities\Product;
use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Models\ProductCategoryModel;
use App\Models\ProductCharacteristicModel;
use App\Models\ProductImageModel;
use App\Models\ProductModel;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function store(Product $product, int $businessId): int
    {
        $productModel = ProductModel::create([
            'business_id' => $businessId,
            'name' => $product->name(),
            'description' => $product->description(),
            'price' => $product->price()->getPrice(),
        ]);
        return (int) $productModel->id;
    }

    public function storeCategories(int $productId, array $categories): void
    {
        ProductCategoryModel::create([
            'product_id' => $productId,
            'category_id' => $categories['categoryId'],
            'subcategory_id' => $categories['subcategoryId'],
            'nested_subcategory_id' => $categories['nestedSubcategoryId'],
        ]);
    }

    public function storeCharacteristics(int $productId, array $characteristics): void
    {
        $productCharacteristics = array_map(function ($characteristic) use ($productId) {
            return [
                'product_id' => $productId,
                'characteristic_key' => $characteristic['characteristic_key'],
                'characteristic_value' => $characteristic['characteristic_value'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $characteristics);

        ProductCharacteristicModel::insert($productCharacteristics);
    }

    public function storeImages(int $productId, array $images): void
    {
        $productImages = array_map(function($img) use ($productId) {
            return [
                'product_id' => $productId,
                'img' => $img,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $images);

        ProductImageModel::insert($productImages);
    }

    public function getPricesByIds(array $productIds): array
    {
        return ProductModel::whereIn('id', $productIds)->pluck('price', 'id')->toArray();
    }
}
