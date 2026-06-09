<?php

namespace App\Services;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryService{
public function getAllCategories()
{
    return Category::all();
}

public function createCategory(array $data){
return Category::create($data);
}

public function updateCategory(Category $category , array $data){
    $category=$category->update( $data);
    return $category;
}

public function deleteCategory(Category $category){

return $category->delete();
}





}