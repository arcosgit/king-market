<?php

namespace App\Http\Controllers;

use App\Application\Product\DTOs\StoreProductData;
use App\Application\Product\UseCases\StoreProductUseCase;
use App\Http\Requests\Product\BuyProductRequest;
use App\Http\Requests\Product\DeleteTemporaryImgRequest;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\TemporarySaveImgRequest;
use App\Http\Resources\Product\ProductShowResource;
use App\Infrastructure\Repositories\EloquentProductRepository;
use App\Models\BalanceModel;
use App\Models\BusinessModel;
use App\Models\OrderModel;
use App\Models\OrderProductModel;
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

    public function show($id)
    {
        $product = ProductModel::where('id', $id)->with('images', 'characteristics', 'business')->first();
        return Inertia::render('product/Show', [
            'product' => ProductShowResource::make($product)->resolve(),
        ]);
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
        $data = StoreProductData::fromRequest($request);
        $use_case = new StoreProductUseCase(new EloquentProductRepository);
        try {
            $result = $use_case->execute($data, auth()->id());
            return response()->json($result);
        } catch(\Exception $e){
            $error_message = $e->getMessage();
            if($error_message === 'no img'){
                return response()->json(['img' => 'no img'], 422);
            }
            return response()->json(['error' => $error_message], 422);
        }
    }

    public function buy(BuyProductRequest $request)
    {
        $data = $request->validated()['products'];
        $productIds = array_column(array_column($data, 'product'), 'id');
        $prices = ProductModel::whereIn('id', $productIds)->pluck('price', 'id')->toArray();
        $total_cost = 0;
        foreach ($data as $item) {
            $productId = $item['product']['id'];
            $quantity = $item['quantity'];
            $total_cost += $prices[$productId] * $quantity;
        }
        $balance = BalanceModel::where('user_id', auth()->id())->first()->amount;
        if($total_cost > $balance){
            return response()->json(['error_balance' => __('balance.not_enough_money')], 422);
        }
        $order = OrderModel::create(['user_id'=>auth()->id(), 'total_cost' => $total_cost]);
        $products = array_map(function($product) use ($order){
            return [
                'order_id' => $order->id,
                'product_id' => $product['product']['id'],
                'quantity' => $product['quantity'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $data);
        OrderProductModel::insert($products);
        BalanceModel::where('user_id', auth()->id())->update(['amount' => $balance - $total_cost]);
        return response()->json(['success' => true, 'balance' => $balance - $total_cost]);
    }
}
