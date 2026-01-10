<?php

namespace App\Application\Product\UseCases;


use App\Application\Product\DTOs\EditProductData;
use App\Domain\Product\Entities\Product;
use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Domain\Product\ValueObjects\Price;
use App\Models\BusinessModel;
use App\Models\TemporaryProductImageModel;
use DB;

final class EditProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $products
    ){}

    public function execute(EditProductData $product_data, int $product_id): array
    {
        try {
            DB::beginTransaction();
            $product = new Product(
                $product_id,
                $product_data->name,
                $product_data->description,
                new Price($product_data->price)
            );
            $this->products->update($product);
            $this->products->updateCategories($product->id(), $product_data->categories);
            $this->products->deleteCharacteristics($product->id());
            if(\count($product_data->characteristics) > 0){
                $this->products->storeCharacteristics($product_id, $product_data->characteristics);
            }
            $this->products->updateImages($product->id(), $product_data->images);
            DB::commit();
            return ['success' => true];
        } catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }
}

