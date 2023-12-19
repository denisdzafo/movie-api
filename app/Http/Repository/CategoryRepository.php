<?php


namespace App\Http\Repository;


use App\Models\Category;

class CategoryRepository
{
    public function getAllCategories()
    {
        return Category::all();
    }
}
