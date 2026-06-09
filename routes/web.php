<?php

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/search-book', function (Request $request) {
$title=$request->title;
$book= Book::select('title')->get();  
return $book;
});


Route::get('search-book-byISBN',function(Request $request){
$ISBN=$request->ISBN;
$book=Book::where('ISBN','LIKE',"%{$ISBN}%")->first();
if($book){
    return apiSuccess("The book is exist",$book,200);
}
else{
   return apiFail("Not found",code:404);
}

});


Route::get('/search-book-category', function (Request $request) {
$category=$request->category;
$category_id=Category::where('name','LIKE',"%{$category}%")->first();
if(!$category_id){
    return apiFail("the Category  not found",code:404);
}
$books = Book::where('category_id',"{$category_id['id']}")->get();    
if($books){
    return apiSuccess("The book is exist",$books,200);
}
else{
   return apiFail("Not found",code:404);
}
});