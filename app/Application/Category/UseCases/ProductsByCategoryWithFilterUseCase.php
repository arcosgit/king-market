<?php

namespace App\Application\Category\UseCases;


use App\Application\Category\DTOs\CategoriesWithAllNestingsIdsFilter;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;
use App\Http\Resources\Product\ProductCardResource;


final class ProductsByCategoryWithFilterUseCase
{
    public function __construct(
        private CategoryRepositoryInterface $category,
    ){}

    public function execute(CategoriesWithAllNestingsIdsFilter $data)
    {
        $category_id = $data->category_id ?? $data->subcategory_id ?? $data->nested_subcategory_id ?? null;
        $category_column = $data->category_id != null ? 'category_id' : ($data->subcategory_id != null ? 'subcategory_id' : 'nested_subcategory_id');
        if($category_id != null){
            $products = $this->category->getProductsByCategory($category_id, $category_column);
            if($data->price_from != null){
                $products->where('price', '>=', $data->price_from)->orderBy('price');
            }
            if($data->price_to != null){
                $products->where('price', '<=', $data->price_to)->orderBy('price');
            }
            $rating = $data->rating;
            if($rating != null){
                $products->whereHas('reviews', function($query) use ($rating){
                    $query->select('product_id')->groupBy('product_id')->havingRaw('ROUND(AVG(rating)) = ?', [$rating]);
                });
            }
            $products = $products->paginate(30);
            return \count($products) != 0 ?
            response()->json(['has_more_page' => $products->hasMorePages(), 'data' => ProductCardResource::collection($products)->resolve()])
            : response()->json(['not_found' => true]);
        } else {
            return response()->json(['empty_categories' => []]);
        }
    }
}

