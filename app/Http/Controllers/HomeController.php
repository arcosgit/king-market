<?php

namespace App\Http\Controllers;

use App\Http\Resources\Category\CategoryWithAllNestingsResource;
use App\Http\Resources\Product\ProductCardResource;
use App\Models\CategoryModel;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('home/Index');
    }

    public function categories()
    {
        $categories = CategoryModel::with('subcategories', 'subcategories.nestedCategories')->get();
        return CategoryWithAllNestingsResource::collection($categories)->resolve();
    }

    public function products(Request $request)
    {
        $cursor = $request->input('cursor');
        $products = ProductModel::with('image', 'reviews', 'favorite')->orderBy('created_at')->cursorPaginate(30, ['*'], 'cursor', $cursor);
        return response()->json([
            'data' => ProductCardResource::collection($products)->resolve(),
            'next_cursor' => $products->nextCursor()?->encode(),
            'has_more' => $products->hasMorePages(),
        ]);
    }
}
