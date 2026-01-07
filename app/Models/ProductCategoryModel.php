<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategoryModel extends Model
{
    protected $table = 'product_categories';
    protected $guarded = [];

    public function product()
    {
        return $this->hasOne(ProductModel::class, 'id', 'product_id');
    }
}
