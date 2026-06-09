<?php

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Models\Author;
use Illuminate\Support\Str;
Route::get('/search-book', function (Request $request) {
$query=Book::query();
$query->when($request->filled('category'),function($q)use($request){
$q->WhereHas('category',function($qCategory)use($request){
$qCategory->where("name","LIKE","%{$request->category}%");
});
});
return apiSuccess( data:$query->get());

});

Route::get('/search-books', function (Request $request) {
$book=Book::all()->load(["category","authors"]);
return $book;
});



Route::get('/search-bookss', function (Request $request) {
$book=Book::with('authors')->orderBy('title')->get();
return $book;
});




Route::get('/search-Category', function (Request $request) {
    $category=Category::with('books')->get();
return $category;
});

Route::get('/search-Count', function (Request $request) {
    $category=Category::withCount('books')->get();
return $category;
});


Route::get('/search-Sum', function (Request $request) {
    $category=Category::withSum('books','rental_price')->get();
return $category;
});


Route::get('/search-Max', function (Request $request) {
    $category=Category::withMax('books','rental_price')->get();
return $category;
});


//Category where book price over 40
Route::get('/search', function (Request $request) {
    $category=Category::with(['books'=>function($q){
    $q->where("rental_price",">",40);
    }])->whereHas('books')->get();
return $category;
});


Route::get('/searchBooks',function(Request $request){
$books=Book::has('authors','>',1)->get()->load(['authors'=>function($q){
    return Str::endsWith('first_name', 'د');
}]);
return $books;
});