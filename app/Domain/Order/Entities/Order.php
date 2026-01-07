<?php

namespace App\Domain\Order\Entities;

use App\Domain\Order\ValueObjects\TotalCost;

final class Order
{
    public function __construct(
        private ?int $id,
        private int $userId,
        private TotalCost $totalCost,
    ){}

    public function id(): ?int
    {
        return $this->id;
    }

    public function userId(): int
    {
        return $this->userId;
    }

    public function totalCost(): TotalCost
    {
        return $this->totalCost;
    }

    public function changeId(int $id): void
    {
        $this->id = $id;
    }
}

