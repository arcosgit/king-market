<?php
namespace App\Domain\Product\Exceptions;

use DomainException;

final class InvalidPriceException extends DomainException
{
    public static function positive(float $price)
    {
        return new self("{$price} must be positive");
    }
}
