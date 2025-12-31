<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\AddNestedSubcategoryRequest;
use App\Http\Requests\Admin\AddSubcategoryRequest;
use App\Http\Requests\Admin\DeleteCategoryRequest;
use App\Http\Requests\Admin\DeleteNestedSubcategoryRequest;
use App\Http\Requests\Admin\DeleteSubcategoryRequest;
use App\Http\Requests\Admin\FindCategoryRequest;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Resources\Category\CategoryWithAllNestingsResource;
use App\Models\CategoryModel;
use App\Models\NestedCategoryModel;
use App\Models\SubcategoryModel;
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

    public function addSubcategory(AddSubcategoryRequest $request)
    {
        $data = $request->validated();
        $is_exist = SubcategoryModel::where('category_id', $data['category_id'])->where('name', $data['name'])->first();
        if($is_exist != null){
            return response()->json(['subcategory_exist' => __('admin.subcategory_exist')], 422);
        }
        $subcategory = SubcategoryModel::create(['category_id' => $data['category_id'], 'name' => $data['name']]);
        return response()->json(['subcategory_id' => $subcategory->id, 'subcategory_name' => $data['name'], 'nested_categories' => []]);
    }

    public function addNestedSubcategory(AddNestedSubcategoryRequest $request)
    {
        $data = $request->validated();
        $is_exist = NestedCategoryModel::where('subcategory_id', $data['subcategory_id'])->where('name', $data['name'])->first();
        if($is_exist != null){
            return response()->json(['nested_subcategory_exist' => __('admin.nested_subcategory_exist')], 422);
        }
        $nested_subcategory = NestedCategoryModel::create(['subcategory_id' => $data['subcategory_id'], 'name' => $data['name']]);
        return response()->json(['nested_category_id' => $nested_subcategory->id, 'nested_category_name' => $data['name']]);
    }

    public function deleteNestedSubcategory(DeleteNestedSubcategoryRequest $request)
    {
        $nested_subcategory_id = $request->validated()['nested_subcategory_id'];
        NestedCategoryModel::where('id', $nested_subcategory_id)->delete();
        return response()->json(['success' => true]);
    }

    public function deleteSubcategory(DeleteSubcategoryRequest $request)
    {
        $subcategory_id = $request->validated()['subcategory_id'];
        SubcategoryModel::where('id', $subcategory_id)->delete();
        return response()->json(['success' => true]);
    }

    public function deleteCategory(DeleteCategoryRequest $request)
    {
        $category_id = $request->validated()['category_id'];
        CategoryModel::where('id', $category_id)->delete();
        return response()->json(['success' => true]);
    }
}
