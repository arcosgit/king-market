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

    public function images()
    {
        return $this->hasMany(ProductImageModel::class, 'product_id', 'id');
    }

    public function characteristics()
    {
        return $this->hasMany(ProductCharacteristicModel::class, 'product_id', 'id');
    }

    public function business()
    {
        return $this->hasOne(BusinessModel::class, 'id', 'business_id');
    }

    public function userReview(){
        return $this->hasOne(ProductReviewModel::class, 'product_id', 'id')->where('user_id', auth()->id());
    }
}
