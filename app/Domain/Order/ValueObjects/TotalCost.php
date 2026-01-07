<?php
namespace App\Domain\Order\ValueObjects;

use App\Domain\Order\Exceptions\InvalidTotalCostException;

final class TotalCost
{
    public function __construct(private int $totalCost)
    {
        if($totalCost < 0){
            throw InvalidTotalCostException::positive($totalCost);
        }
    }

    public function getTotalCost(): int
    {
        return $this->totalCost;
    }
}

