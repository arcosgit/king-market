<?php

namespace App\Domain\Product\Entities;

use App\Domain\Product\ValueObjects\Price;


final class Product
{

    public function __construct(
        private ?int $id,
        private string $name,
        private string $description,
        private Price $price,
    ){}
    public function id(){ return $this->id; }
    public function name(){ return $this->name; }
    public function description(){ return $this->description; }
    public function price(){ return $this->price; }

}
