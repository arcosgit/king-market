<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\FindCategoryRequest;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Resources\Category\CategoryWithAllNestingsResource;
use App\Models\CategoryModel;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function createCategory(StoreCategoryRequest $request)
    {
        $name = $request->validated()['name'];
        CategoryModel::create(['name' => $name]);
        return response()->json(['success' => true]);
    }

    public function findCategory(FindCategoryRequest $request)
    {
        $category_name = $request->validated()['name'];
        $all_nestings = CategoryModel::where('name', $category_name)->with('subcategories', 'subcategories.nestedCategories')->first();
        return CategoryWithAllNestingsResource::make($all_nestings)->resolve();
    }
}
