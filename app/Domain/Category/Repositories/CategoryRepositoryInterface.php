<?php
namespace App\Domain\Category\Repositories;

use Illuminate\Database\Eloquent\Builder;





interface CategoryRepositoryInterface
{
    public function getProductsByCategory(int $category_id, string $category_column): Builder;
}
