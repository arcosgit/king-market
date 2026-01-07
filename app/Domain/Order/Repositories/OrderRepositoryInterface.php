<?php
namespace App\Domain\Order\Repositories;

use App\Domain\Order\Entities\Order;

interface OrderRepositoryInterface
{
    public function store(Order $order): int;
    public function storeProducts(int $orderId, array $products): void;
}

