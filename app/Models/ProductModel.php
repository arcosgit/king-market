<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    protected $table = "products";
    protected $guarded = [];

    public function image()
    {
        return $this->hasOne(ProductImageModel::class, 'product_id', 'id');
    }
}
