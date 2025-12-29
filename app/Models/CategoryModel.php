<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';

    protected $guarded = [];

    public function subcategories()
    {
        return $this->hasMany(SubcategoryModel::class, 'category_id', 'id');
    }
}
