<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Models\Category;
use App\Models\Book;
use App\Models\Author;
use App\Models\Remove_Frome_remaining;
use App\Http\Controllers\Remove_Frome_remainingController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('categories',CategoryController::class);
Route::apiResource("authors",AuthorController::class);
Route::apiResource("books",BookController::class);
Route::apiResource("remove_frome_remaining",Remove_Frome_remainingController::class);
Route::get('categoryhasbooks',function(){
    $category=Category::has('books','>',0)->get();  
     return $category;
});

Route::prefix('/Counts')->group(function(){
Route::get('/Books',function(){
$books=Book::all()->count();
return $books;
});

Route::get('/category',function(){
$category=Category::all()->count();
return $category;
});

Route::get('/author',function(){
$author=Author::all()->count();
return $author;
});

Route::get('/AddStock',function(){
$author=Remove_Frome_remaining::all()->where('type',"LIKE","add")->sum('quantity');
return $author;
});

Route::get('/hasNobook',function(){
$author=Author::has('books',"=",0)->count();
return $author;
});

Route::get('/users',function(){
$user=User::all()->count();
return $user;
});


});
Route::get('book-search',[BookController::class,'Search_Book']);
Route::controller(AuthController::class)->group(function () {
    Route::post('/signup', 'signup');
    Route::post('/login', 'login');
    Route::post('/send-otp', 'send_otp');
    Route::post('/verify-otp', 'verify_otp');
    Route::middleware('auth:sanctum')->post('/logout', 'logout');
    Route::middleware('auth:sanctum')->get('/user', 'user');
}); 
