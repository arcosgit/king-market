<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubcategoryModel extends Model
{
    protected $table = 'subcategories';

    protected $guarded = [];

    public function nestedCategories()
    {
        return $this->hasMany(NestedCategoryModel::class, 'subcategory_id', 'id');
    }
}
