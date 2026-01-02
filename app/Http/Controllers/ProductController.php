<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\DeleteTemporaryImgRequest;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\TemporarySaveImgRequest;
use App\Models\BusinessModel;
use App\Models\ProductCategoryModel;
use App\Models\ProductCharacteristicModel;
use App\Models\ProductImageModel;
use App\Models\ProductModel;
use App\Models\TemporaryProductImageModel;
use DB;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Storage;

class ProductController extends Controller
{
    public function create()
    {
        return Inertia::render('product/Create');
    }

    public function temporarySaveImg(TemporarySaveImgRequest $request)
    {
        $img = $request->validated()['img'];
        $business = BusinessModel::where('user_id', auth()->id())->first();
        if($business == null){
            return response()->json(['error_business' => __('business.no_business')]);
        }
        $path = Storage::disk('public')->put('/images', $img);
        $temp_img = TemporaryProductImageModel::create(['business_id'=> $business->id, 'temp_img' => $path]);
        return response()->json(['img_id' => $temp_img->id, 'path' => url('build/storage/' . $path)]);
    }

    public function deleteTemporaryImg(DeleteTemporaryImgRequest $request)
    {
        $img_id = $request->validated()['img_id'];
        $img = TemporaryProductImageModel::where('id', $img_id)->first();
        Storage::disk('public')->delete($img->temp_img);
        $img->delete();
        return response()->json(['success' => true]);
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $business = BusinessModel::where('user_id', auth()->id())->first();
        try{
            DB::beginTransaction();
            $product = ProductModel::create(['business_id' => $business->id, 'name' => $data['name'], 'description' => $data['description'], 'price' => $data['price']]);
            ProductCategoryModel::create(['product_id' => $product->id, 'category_id' => $data['categoryId'], 'subcategory_id' => $data['subcategoryId'], 'nested_subcategory_id' => $data['nestedSubcategoryId']]);
            if(\count($data['characteristics']) > 0){
                $productCharacteristics = array_map(function ($characteristic) use ($product) {
                    return [
                        'product_id' => $product->id,
                        'characteristic_key' => $characteristic['characteristic_key'],
                        'characteristic_value' => $characteristic['characteristic_value'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }, $data['characteristics']);
                ProductCharacteristicModel::insert($productCharacteristics);
            }
            $temp_imgs = TemporaryProductImageModel::where('business_id', $business->id)->get('temp_img');
            if(count($temp_imgs) == 0){
                return response()->json(['img' => 'no img'], 422);
            }
            $imgs = array_map(function($img) use ($product){
                return [
                    'product_id' => $product->id,
                    'img' => $img['temp_img'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $temp_imgs->toArray());
            ProductImageModel::insert($imgs);
            DB::commit();
        } catch(\Exception $e){
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
        return response()->json(['success' => true]);
    }
}
