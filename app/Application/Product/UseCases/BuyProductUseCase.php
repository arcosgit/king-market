<?php

namespace App\Application\Product\UseCases;

use App\Application\Product\DTOs\BuyProductData;
use App\Domain\Balance\Repositories\BalanceRepositoryInterface;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Domain\Order\ValueObjects\TotalCost;
use App\Domain\Product\Repositories\ProductRepositoryInterface;
use DB;

final class BuyProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $products,
        private OrderRepositoryInterface $orders,
        private BalanceRepositoryInterface $balance
    ){}

    public function execute(BuyProductData $buyData, int $userId): array
    {
        try {
            DB::beginTransaction();
            $productIds = array_column(array_column($buyData->products, 'product'), 'id');
            $prices = $this->products->getPricesByIds($productIds);
            $totalCost = 0;
            foreach ($buyData->products as $item) {
                $productId = $item['product']['id'];
                $quantity = $item['quantity'];
                $totalCost += $prices[$productId] * $quantity;
            }
            $currentBalance = $this->balance->getBalance($userId);
            if($totalCost > $currentBalance){
                DB::rollBack();
                throw new \Exception(__('balance.not_enough_money'));
            }
            $order = new Order(null, $userId, new TotalCost($totalCost));

            $orderId = $this->orders->store($order);

            $orderProducts = array_map(function($product) {
                return [
                    'product_id' => $product['product']['id'],
                    'quantity' => $product['quantity'],
                ];
            }, $buyData->products);

            $this->orders->storeProducts($orderId, $orderProducts);

            $newBalance = $currentBalance - $totalCost;
            $this->balance->updateBalance($userId, $newBalance);

            DB::commit();

            return ['success' => true, 'balance' => $newBalance];
        } catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }
}

