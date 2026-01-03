<?php

namespace App\Application\Product\UseCases;

use App\Application\Product\DTOs\StoreProductData;
use App\Domain\Product\Entities\Product;
use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Domain\Product\ValueObjects\Price;
use App\Models\BusinessModel;
use App\Models\TemporaryProductImageModel;
use DB;

final class StoreProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $products
    ){}

    public function execute(StoreProductData $productData, int $userId): array
    {
        $business = BusinessModel::where('user_id', $userId)->first();
        if($business == null){
            throw new \Exception(__('business.no_business'));
        }
        try {
            DB::beginTransaction();

            $product = new Product(
                null,
                $productData->name,
                $productData->description,
                new Price($productData->price)
            );
            $productId = $this->products->store($product, $business->id);
            $this->products->storeCategories($productId, $productData->categories);
            if(count($productData->characteristics) > 0){
                $this->products->storeCharacteristics($productId, $productData->characteristics);
            }
            $tempImages = TemporaryProductImageModel::where('business_id', $business->id)->get();
            if(count($tempImages) == 0){
                DB::rollBack();
                throw new \Exception('no img');
            }
            $images = array_map(function($img) {
                return $img['temp_img'];
            }, $tempImages->toArray());
            $this->products->storeImages($productId, $images);
            TemporaryProductImageModel::where('business_id', $business->id)->delete();
            DB::commit();
            return ['success' => true];
        } catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }
}

