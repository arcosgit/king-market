<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFavoriteModel extends Model
{
    protected $table = "product_favorite";
    protected $guarded = [];

    public function product()
    {
        return $this->hasOne(ProductModel::class, 'id', 'product_id');
    }
}
