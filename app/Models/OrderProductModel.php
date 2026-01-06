<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProductModel extends Model
{
    protected $table = 'order_products';

    protected $guarded = [];

    public function product()
    {
        return $this->hasOne(ProductModel::class, 'id', 'product_id');
    }
}
