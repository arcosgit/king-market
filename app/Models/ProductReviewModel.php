<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReviewModel extends Model
{
    protected $table = 'product_reviews';
    protected $guarded = [];

    public function user(){
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }
}
