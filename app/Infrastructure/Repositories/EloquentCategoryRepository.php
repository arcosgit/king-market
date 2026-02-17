<?php
namespace App\Infrastructure\Repositories;

use App\Domain\Category\Repositories\CategoryRepositoryInterface;
use App\Models\ProductModel;
use Illuminate\Database\Eloquent\Builder;


class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function getProductsByCategory(int $category_id, string $category_column): Builder
    {
        return ProductModel::with('image', 'reviews', 'favorite')->whereHas('categories', function($query) use ($category_id, $category_column){
            $query->where($category_column, $category_id);
        })->join('product_categories', 'products.id', '=', 'product_categories.product_id')
        ->when($category_column === 'category_id', function ($query) {
            $query->orderByRaw('(product_categories.subcategory_id IS NULL AND product_categories.nested_subcategory_id IS NULL) DESC');
        })->when($category_column === 'subcategory_id', function ($query) {
            $query->orderByRaw('(product_categories.nested_subcategory_id IS NULL) DESC');
        })->select('products.*');
    }
}
