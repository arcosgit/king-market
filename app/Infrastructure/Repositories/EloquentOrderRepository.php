<?php
namespace App\Infrastructure\Repositories;

use App\Domain\Order\Entities\Order;
use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Models\OrderModel;
use App\Models\OrderProductModel;

class EloquentOrderRepository implements OrderRepositoryInterface
{
    public function store(Order $order): int
    {
        $orderModel = OrderModel::create([
            'user_id' => $order->userId(),
            'total_cost' => $order->totalCost()->getTotalCost(),
        ]);
        return (int) $orderModel->id;
    }

    public function storeProducts(int $orderId, array $products): void
    {
        $orderProducts = array_map(function($product) use ($orderId) {
            return [
                'order_id' => $orderId,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $products);

        OrderProductModel::insert($orderProducts);
    }
}

