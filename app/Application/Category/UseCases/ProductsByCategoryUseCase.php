<?php

namespace App\Application\Category\UseCases;

use App\Application\Category\DTOs\CategoriesWithAllNestingsIds;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;
use App\Http\Resources\Product\ProductCardResource;


final class ProductsByCategoryUseCase
{
    public function __construct(
        private CategoryRepositoryInterface $category,
    ){}

    public function execute(CategoriesWithAllNestingsIds $data)
    {
        $category_id = $data->category_id ?? $data->subcategory_id ?? $data->nested_subcategory_id ?? null;
        $category_column = $data->category_id != null ? 'category_id' : ($data->subcategory_id != null ? 'subcategory_id' : 'nested_subcategory_id');
        if($category_id != null){
            $products = $this->category->getProductsByCategory($category_id, $category_column);
            return ProductCardResource::collection($products->paginate(30))->resolve();
        } else {
            return response()->json(['empty_categories' => []]);
        }
    }
}

