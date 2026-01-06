<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    protected $table = 'orders';

    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(OrderProductModel::class, 'order_id', 'id');
    }
}
