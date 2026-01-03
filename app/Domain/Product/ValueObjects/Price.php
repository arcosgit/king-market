<?php
namespace App\Domain\Product\ValueObjects;

use App\Domain\Product\Exceptions\InvalidPriceException;

final class Price
{

    public function __construct(private float $price){
        if($price < 0){
            throw InvalidPriceException::positive($price);
        }
    }

    public function getPrice()
    {
        return $this->price;
    }
}
