<?php
namespace App\Domain\Order\Exceptions;

use DomainException;

final class InvalidTotalCostException extends DomainException
{
    public static function positive(int $totalCost)
    {
        return new self("{$totalCost} must be positive");
    }
}

