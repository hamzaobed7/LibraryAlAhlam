<?php


use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Book_requestController;
use App\Http\Controllers\CustomerController;
use App\Models\Category;
use App\Models\Book;
use App\Models\Author;
use App\Models\Remove_Frome_remaining;
use App\Http\Controllers\Remove_Frome_remainingController;
use App\Http\Controllers\UserController;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('categories',CategoryController::class)->only('index','show');
Route::apiResource("authors",AuthorController::class)->only('index','show');
Route::apiResource("books",BookController::class)->only('index','show','Search_Book');
Route::apiResource('/book_request',Book_requestController::class)->only('index','show');


Route::middleware(['auth:sanctum','user_type:admin'])->group(function(){
Route::apiResource('categories',CategoryController::class)->except('index','show');
Route::apiResource("authors",AuthorController::class)->except('index','show');
Route::apiResource("books",BookController::class)->except('index','show','Search_Book');
Route::apiResource("remove_frome_remaining",Remove_Frome_remainingController::class);
Route::patch('/admin/profile',[UserController::class,'updateAdmin']);

});

Route::middleware(['auth:sanctum','user_type:customer'])->group(function () {
    Route::put('/UpdateProfile', [CustomerController::class, 'update']);
    Route::get('/MyProfile', [CustomerController::class, 'profile']);
    Route::apiResource('/book_request',Book_requestController::class)->except('index','show');
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);          
        Route::post('/', [CartController::class, 'store']);        
        Route::delete('/{id}', [CartController::class, 'destroy']); 
    });
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

Route::get('users',function(){
return Customer::count();
});


});






Route::prefix('/deletemulti')->group(function(){
Route::delete('/author',function (Request $request) {
    $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'exists:authors,id'
    ]);
    $ids = $request->input('ids'); 
    Author::whereIn('id', $ids)->delete();
    return apiSuccess("تم الحذف بنجاح", code: 200); 
   });
   Route::delete('/books',function (Request $request) {
    $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'exists:authors,id'
    ]);
    $ids = $request->input('ids'); 
    Book::whereIn('id', $ids)->delete();
    return apiSuccess("تم الحذف بنجاح", code: 200); 
});
});

Route::get('book-search',[BookController::class,'Search_Book']);


//for return Category that have books
Route::get('categoryhasbooks',function(){
    $category=Category::has('books','>',0)->get();  
     return $category;
});



//for Landing page
Route::get('/treandBook',function(){
$books=Book::with('category')->take(6)->get();
return $books;
});



Route::controller(AuthController::class)->group(function () {
    Route::post('/signup', 'signup');
    Route::post('/login', 'login');
    Route::post('/verify_otp', 'verify_otp');
    Route::middleware('auth:sanctum')->post('/logout', 'logout');
    Route::middleware('auth:sanctum')->get('/user', 'user');
}); 


