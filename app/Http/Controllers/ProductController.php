<?php

namespace App\Http\Controllers;

use App\Application\Product\DTOs\BuyProductData;
use App\Application\Product\DTOs\EditProductData;
use App\Application\Product\DTOs\StoreProductData;
use App\Application\Product\UseCases\BuyProductUseCase;
use App\Application\Product\UseCases\EditProductUseCase;
use App\Application\Product\UseCases\StoreProductUseCase;
use App\Http\Requests\Catalog\CatalogRequest;
use App\Http\Requests\Product\BuyProductRequest;
use App\Http\Requests\Product\CreateReviewProductRequest;
use App\Http\Requests\Product\DeleteTemporaryImgRequest;
use App\Http\Requests\Product\EditProductRequest;
use App\Http\Requests\Product\FindFilterProductRequest;
use App\Http\Requests\Product\FindProductRequest;
use App\Http\Requests\Product\ReviewsProductRequest;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\TemporarySaveImgRequest;
use App\Http\Resources\Product\ProductCardResource;
use App\Http\Resources\Product\ProductEditResource;
use App\Http\Resources\Product\ProductReviewResource;
use App\Http\Resources\Product\ProductShowResource;
use App\Infrastructure\Repositories\EloquentBalanceRepository;
use App\Infrastructure\Repositories\EloquentOrderRepository;
use App\Infrastructure\Repositories\EloquentProductRepository;
use App\Models\BusinessModel;
use App\Models\OrderProductModel;
use App\Models\ProductImageModel;
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
        return Inertia::render('product/Show', [
            'product_id' => $id,
        ]);
    }

    public function showProduct(Request $request){
        $product_id = $request->validate(['product_id' => ['required', 'integer', 'exists:products,id']])['product_id'];
        $product = ProductModel::where('id', $product_id)->with('images', 'characteristics', 'business', 'reviews', 'categories', 'favorite')->first();
        $similar_products = ProductModel::where('id', '!=', $product_id)->whereHas('categories', function ($query) use ($product) {
            $cat = $product->categories;
            $query->when($cat->category_id !== null, fn ($q) => $q->where('category_id', $cat->category_id))
                ->when($cat->subcategory_id !== null, fn ($q) => $q->orWhere('subcategory_id', $cat->subcategory_id))
                ->when($cat->nested_subcategory_id !== null, fn ($q) => $q->orWhere('nested_subcategory_id', $cat->nested_subcategory_id));
            })->with('image', 'reviews')->cursorPaginate(30);
        return response()->json([
            'products' => ProductShowResource::make($product)->resolve(),
            'similar_product' => ProductCardResource::collection($similar_products)->resolve(),
        ]);
    }

    public function reviews(ReviewsProductRequest $request){
        $data = $request->validated();
        $cursor = $request->input('cursor');
        $query = ProductReviewModel::where('product_id', $data['product_id'])->with('user');
        if($data['rating'] != 'last'){
            $query->where('rating', $data['rating']);
        }
        $reviews = $query->orderByDesc('created_at')->cursorPaginate(20, ['*'], 'cursor', $cursor);
        return response()->json([
            'data' => ProductReviewResource::collection($reviews)->resolve(),
            'next_cursor' => $reviews->nextCursor()?->encode(),
            'has_more' => $reviews->hasMorePages(),
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
        $isProductBought = OrderProductModel::where('order_id', $data['order_id'])->where('product_id', $data['product_id'])->first();
        if($isProductBought == null){
            return response()->json(['error_product' => __('product.product_review_not_bought')], 422);
        }
        ProductReviewModel::updateOrCreate(
        ['product_id' => $data['product_id'], 'user_id' => auth()->id()],
        ['review' => $data['review'], 'rating' => $data['rating']]);
        return response()->json(['succes' => true]);
    }

    public function edit(Request $request)
    {
        $product = $request->attributes->get('product');
        return Inertia::render('product/Edit', ['product' => ProductEditResource::make($product)->resolve()]);
    }

    public function editSave(EditProductRequest $request)
    {
        $data = EditProductData::fromRequest($request);
        $product = $request->attributes->get('product');
        $use_case = new EditProductUseCase(new EloquentProductRepository);
        try {
            $result = $use_case->execute($data, $product->id);
            return response()->json($result);
        } catch(\Exception $e){
            $error_message = $e->getMessage();
            return response()->json(['error' => $error_message], 422);
        }
    }

    public function deleteProduct(Request $request)
    {
        $product = $request->attributes->get('product');
        $images = ProductImageModel::where('product_id', $product->id)->get();
        foreach ($images as $image) {
            Storage::disk('public')->delete($image->img);
        }
        $product->delete();
        return response()->json(['success' => true]);
    }


    public function saveImg(TemporarySaveImgRequest $request, $id)
    {
        $img = $request->validated()['img'];
        $path = Storage::disk('public')->put('/images', $img);
        $img_hide = ProductImageModel::create(['product_id' => $id, 'img' => $path, 'hide' => true]);
        return response()->json(['img_id' => $img_hide->id, 'path' => url('build/storage/' . $path), 'hide' => true]);
    }

    public function find(FindProductRequest $request)
    {
        $data = $request->validated();
        if($data['business_id'] != null){
            if(ctype_digit($data['name'])){
                $product = ProductModel::where('business_id', $data['business_id'])->where('id', $data['name'])->with('image', 'reviews', 'favorite')->first();
                return $product != null ? ProductCardResource::make($product)->resolve() : response()->json(['not_found' => true]);
            } else {
                $products = ProductModel::where('business_id', $data['business_id'])->whereLike('name', '%' . $data['name'] . '%')->with('image', 'reviews', 'favorite')->cursorPaginate(30, ['*'], 'cursor', $data['cursor']);
                return count($products) != 0 ?
                response()->json(['data' => ProductCardResource::collection($products)->resolve(), 'next_cursor' => $products->nextCursor()?->encode(), 'has_more' => $products->hasMorePages()]) :
                response()->json(['not_found' => true]);
            }
        } else {
            if(ctype_digit($data['name'])){
                $product = ProductModel::where('id', $data['name'])->with('images', 'characteristics', 'business', 'reviews', 'favorite')->first();
                return $product != null ? ProductShowResource::make($product)->resolve() : response()->json(['not_found' => true]);
            } else {
                $products = ProductModel::whereLike('name', '%' . $data['name'] . '%')->with('image', 'reviews', 'favorite')->cursorPaginate(30, ['*'], 'cursor', $data['cursor']);
                return count($products) != 0 ?
                response()->json(['data' => ProductCardResource::collection($products)->resolve(), 'next_cursor' => $products->nextCursor()?->encode(), 'has_more' => $products->hasMorePages()]) :
                response()->json(['not_found' => true]);
            }
        }
    }

    public function findFilter(FindFilterProductRequest $request)
    {
        $data = $request->validated();
        $products = ProductModel::whereLike('name', '%' . $data['name'] . '%')->with('image', 'reviews', 'favorite');
        if($data['price_from'] != null){
            $products->where('price', '>=', $data['price_from'])->orderBy('price');
        }
        if($data['price_to'] != null){
            $products->where('price', '<=', $data['price_to'])->orderBy('price');
        }
        $rating = $data['rating'];
        if($rating != null){
            $products->whereHas('reviews', function($query) use ($rating){
                $query->select('product_id')
                    ->groupBy('product_id')
                    ->havingRaw('ROUND(AVG(rating)) = ?', [$rating]);
            });
        }
        $products = $products->cursorPaginate(30, ['*'], 'cursor', $data['cursor']);
        return count($products) != 0 ?
        response()->json(['data' => ProductCardResource::collection($products)->resolve(), 'next_cursor' => $products->nextCursor()?->encode(), 'has_more' => $products->hasMorePages()]) :
        response()->json(['not_found' => true]);
    }

    public function catalog(CatalogRequest $request)
    {
        $data = $request->validated();
        $category_id = $data['category_id'] ?? $data['subcategory_id'] ?? $data['nested_subcategory_id'] ?? null;
        $category_column = $data['category_id'] != null ? 'category_id' : ($data['subcategory_id'] != null ? 'subcategory_id' : 'nested_subcategory_id');
        if($category_id != null){
            $products = ProductModel::whereHas('categories', function($query) use ($category_id, $category_column){
                $query->where($category_column, $category_id);
            })->join('product_categories', 'products.id', '=', 'product_categories.product_id')->when($category_column === 'category_id', function ($query) {
                $query->orderByRaw('(product_categories.subcategory_id IS NULL AND product_categories.nested_subcategory_id IS NULL) DESC');
            })->when($category_column === 'subcategory_id', function ($query) {
                $query->orderByRaw('(product_categories.nested_subcategory_id IS NULL) DESC');
            })->select('products.*')->get();
            return ProductCardResource::collection($products)->resolve();
        } else {
            return response()->json(['empty_categories' => []]);
        }
    }

    public function catalogShow()
    {
        return Inertia::render('product/Catalog');
    }
}
