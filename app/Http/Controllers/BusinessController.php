<?php

namespace App\Http\Controllers;

use App\Http\Requests\Business\StoreBusinessRequest;
use App\Http\Resources\Business\BusinessResource;
use App\Http\Resources\Product\ProductCardResource;
use App\Models\BusinessModel;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function getBusiness()
    {
        $business = BusinessModel::where('user_id', auth()->id())->first();
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
        $products = BusinessModel::where('user_id', auth()->id())->with('products', 'products.image', 'products.favorite')->get()->pluck('products')->flatten();
        return ProductCardResource::collection($products)->resolve();
    }
}
