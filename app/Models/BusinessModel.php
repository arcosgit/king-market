<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessModel extends Model
{
    protected $table = 'business';
    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(ProductModel::class, 'business_id', 'id');
    }

    public function getTotalSoldQuantityAttribute()
    {
        return OrderProductModel::whereHas('product', function ($query) {
            $query->where('business_id', $this->id);
        })->sum('quantity');
    }

    public function getTotalRatingReviewsAttribute()
    {
        $reviews = ProductReviewModel::whereHas('product', function($query){
            $query->where('business_id', $this->id);
        });
        return [
            'average_rating' => floor($reviews->sum('rating') / $reviews->count() * 10) / 10,
            'quantity_reviews' => $reviews->count(),
        ];
    }
}
