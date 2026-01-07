<?php

namespace App\Http\Controllers;

use App\Application\Product\DTOs\BuyProductData;
use App\Application\Product\DTOs\StoreProductData;
use App\Application\Product\UseCases\BuyProductUseCase;
use App\Application\Product\UseCases\StoreProductUseCase;
use App\Http\Requests\Product\BuyProductRequest;
use App\Http\Requests\Product\CreateReviewProductRequest;
use App\Http\Requests\Product\DeleteTemporaryImgRequest;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\TemporarySaveImgRequest;
use App\Http\Resources\Product\ProductShowResource;
use App\Infrastructure\Repositories\EloquentBalanceRepository;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use App\Infrastructure\Repositories\EloquentProductRepository;
use App\Models\BusinessModel;
use App\Models\ProductModel;
use App\Models\ProductReviewModel;
use App\Models\TemporaryProductImageModel;
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
        $data = BuyProductData::fromRequest($request);
        $use_case = new BuyProductUseCase(
            new EloquentProductRepository,
            new EloquentOrderRepository,
            new EloquentBalanceRepository
        );
        try {
            $result = $use_case->execute($data, auth()->id());
            return response()->json($result);
        } catch(\Exception $e){
            $error_message = $e->getMessage();
            if($error_message === __('balance.not_enough_money')){
                return response()->json(['error_balance' => $error_message], 422);
            }
            return response()->json(['error' => $error_message], 422);
        }
    }

    public function createReview(CreateReviewProductRequest $request){
        $data = $request->validated();
        ProductReviewModel::updateOrCreate(
        ['product_id' => $data['product_id'], 'user_id' => auth()->id()],
        ['review' => $data['review'], 'rating' => $data['rating']]);
        return response()->json(['succes' => true]);
    }
}
