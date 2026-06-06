<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use App\Http\Helper\ResponseHelper;
use Illuminate\Http\JsonResponse;
class CategoryController extends Controller
{
    
    public function index():JsonResponse
    {
        $category=Category::all();
        return apiSuccess("All Categories",$category,200);
    }

    
    public function store(CategoryRequest $request):JsonResponse
    {
        $data=$request->validated();
        $category = Category::create($data);
        return apiSuccess("Category created successfully",$category,201);
    }

    
    public function show(Category $category ):JsonResponse
    {
        return apiSuccess("Category Specific",$category,200);
    }

    public function update(CategoryRequest $request, Category $category):JsonResponse
    {
       $data=$request->validated();
       $category=$category->update($data);
        return apiSuccess("Category updated successfully",$category,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category):JsonResponse
    {
        $category->delete();
        return apiSuccess("Category deleted successfully",200);
    }
}
