<?php

namespace App\Http\Controllers;

use App\Http\Requests\Business\StoreBusinessRequest;
use App\Http\Resources\Business\BusinessResource;
use App\Http\Resources\Product\ProductCardResource;
use App\Models\BusinessModel;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BusinessController extends Controller
{
    public function getBusiness()
    {
        $business = BusinessModel::where('user_id', auth()->id())->withCount('products')->first();
        return BusinessResource::make($business)->resolve();
    }

    public function create(StoreBusinessRequest $request)
    {
        $name = $request->validated()['name'];
        $business = BusinessModel::where('user_id', auth()->id())->first();
        if($business != null){
            return response()->json(['error' => 'One user can only have one brand.']);
        }
        $business = BusinessModel::create(['user_id' => auth()->id(), 'name' => $name]);
        return response()->json(['success' => true, 'name' => $name, 'id' => $business->id]);
    }

    public function changeName(StoreBusinessRequest $request)
    {
        $name = $request->validated()['name'];
        BusinessModel::where('user_id', auth()->id())->first()->update(['name' => $name]);
        return response()->json(['success' => true, 'name' => $name]);
    }

    public function getProducts()
    {
        $business = BusinessModel::where('user_id', auth()->id())->first();
        $products = ProductModel::where('business_id', $business->id)->with('image', 'reviews', 'favorite')
        ->orderByDesc('created_at')->cursorPaginate(20);
        return response()->json([
            'data' => ProductCardResource::collection($products)->resolve(),
            'next_cursor' => $products->nextCursor()?->encode(),
            'has_more' => $products->hasMorePages(),
        ]);
    }

    public function showProducts($name)
    {
        $business = BusinessModel::where('name', $name)->first();
        return Inertia::render('business/ShowProducts', ['name' => $name, 'id' => $business->id]);
    }

    public function getProductsFromName($id)
    {
        $products = ProductModel::where('business_id', $id)->with('image', 'reviews', 'favorite')
        ->orderByDesc('created_at')->cursorPaginate(30);
        return response()->json([
            'data' => ProductCardResource::collection($products)->resolve(),
            'next_cursor' => $products->nextCursor()?->encode(),
            'has_more' => $products->hasMorePages(),
        ]);
    }
}
