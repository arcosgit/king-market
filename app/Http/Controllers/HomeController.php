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

    public function products()
    {
        $products = ProductModel::with('image')->get();
        return ProductCardResource::collection($products)->resolve();
    }
}
