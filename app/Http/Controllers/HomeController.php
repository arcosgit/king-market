<?php

namespace App\Http\Controllers;

use App\Http\Resources\Category\CategoryWithAllNestingsResource;
use App\Models\CategoryModel;
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
}
