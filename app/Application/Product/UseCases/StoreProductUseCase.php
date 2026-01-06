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

    public function execute(StoreProductData $product_data, int $user_id): array
    {
        $business = BusinessModel::where('user_id', $user_id)->first();
        if($business == null){
            throw new \Exception(__('business.no_business'));
        }
        try {
            DB::beginTransaction();

            $product = new Product(
                null,
                $product_data->name,
                $product_data->description,
                new Price($product_data->price)
            );
            $product_id = $this->products->store($product, $business->id);
            $this->products->storeCategories($product_id, $product_data->categories);
            if(\count($product_data->characteristics) > 0){
                $this->products->storeCharacteristics($product_id, $product_data->characteristics);
            }
            $temp_images = TemporaryProductImageModel::where('business_id', $business->id)->get();
            if(\count($temp_images) == 0){
                DB::rollBack();
                throw new \Exception('no img');
            }
            $images = array_map(function($img) {
                return $img['temp_img'];
            }, $temp_images->toArray());
            $this->products->storeImages($product_id, $images);
            TemporaryProductImageModel::where('business_id', $business->id)->delete();
            DB::commit();
            return ['success' => true];
        } catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }
}

