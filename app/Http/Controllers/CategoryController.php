<?php

namespace App\Http\Controllers;

use App\Http\Repository\CategoryRepository;

class CategoryController extends Controller
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository )
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories()
    {
        $categories = $this->categoryRepository->getAllCategories();

        return response()->json($categories, 200);
    }

}
