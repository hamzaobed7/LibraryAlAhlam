<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use App\Http\Helper\ResponseHelper;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    public function index(): JsonResponse
    {
        return apiSuccess("All Categories", $this->categoryService->getAllCategories(), 200);
    }


    public function store(CategoryRequest $request): JsonResponse
    {
        $data = $request->validated();
        $category = $this->categoryService->createCategory($data);
        return apiSuccess("Category created successfully", $category, 201);
    }


    public function show(Category $category): JsonResponse
    {
        return apiSuccess("Category Specific", $category, 200);
    }

    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        $data = $request->validated();
        $category = $this->categoryService->updateCategory($category, $data);
        return apiSuccess("Category updated successfully", $category, 200);
    }


    public function destroy(Category $category): JsonResponse
    {
        $this->categoryService->deleteCategory($category);
        return apiSuccess("Category deleted successfully", null, 200);
    }

    public function CategoryCount()
    {

        return Cache::remember('CountCategory', 3600, fn() => Category::count());
    }

    public function HasBook()
    {
        return Cache::remember('HasBook', 3600, fn() => Category::has('books',)->get());
    }
}
