<?php


use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\Book_requestController;
use App\Http\Controllers\CustomerController;
use App\Models\Category;
use App\Models\Book;
use App\Models\Author;
use App\Models\Remove_Frome_remaining;
use App\Http\Controllers\Remove_Frome_remainingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WatingListController;
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
Route::get('/requests',[WatingListController::class,'index']);
Route::get('/requests/{id}',[WatingListController::class,'show']);
Route::get('/customers',[CustomerController::class,'index']);

Route::middleware(['auth:sanctum','user_type:admin'])->group(function(){
Route::apiResource('categories',CategoryController::class)->except('index','show');
Route::apiResource("authors",AuthorController::class)->except('index','show');
Route::apiResource("books",BookController::class)->except('index','show');
Route::apiResource("remove_frome_remaining",Remove_Frome_remainingController::class);
 Route::apiResource('/bill',BillController::class)->except('index',"show")->only('index','show');
Route::patch('/admin/profile',[UserController::class,'updateAdmin']);
Route::get('/customers/{customer}',[CustomerController::class,'show']);
Route::patch('/upstatus/{book_request}', [Book_requestController::class, 'updateStatus']);
});

Route::middleware(['auth:sanctum','user_type:customer'])->group(function () {
    Route::put('/UpdateProfile', [CustomerController::class, 'update']);
    Route::get('/MyProfile', [CustomerController::class, 'profile']);
    Route::apiResource('/book_request',Book_requestController::class)->except('index','show');
    Route::apiResource('/bill',BillController::class)->except('index',"show");
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);          
        Route::post('/', [CartController::class, 'store']);        
        Route::delete('/{id}', [CartController::class, 'destroy']); 
        Route::get('/count',[CartController::class,'CountCart']);
    });
    Route::post('/request-list',[WatingListController::class,'store']);

    Route::get('/requsts',[CustomerController::class,'AllRequest']);
});


Route::prefix('/Counts')->group(function(){
Route::get('/Books',[BookController::class,'bookCount']);
Route::get('/category',[CategoryController::class,'CategoryCount']);
Route::get('/author',[AuthorController::class,'AuthorCount']);

Route::get('/AddStock',[Remove_Frome_remainingController::class,'theOperationAdd']);

Route::get('/hasNobook',[AuthorController::class,'HasNoBook']);
Route::get('users',[CustomerController::class,'CustomrtCount']);

});



Route::prefix('/deletemulti')->group(function(){
Route::delete('/author',[AuthorController::class,'DeleteManyAuthor']);
Route::delete('/books',[BookController::class,'DeleteManyBook']);
});

Route::get('book-search',[BookController::class,'Search_Book']);


//for return Category that have books
Route::get('categoryhasbooks',[CategoryController::class,'HasBook']);



//for Landing page
Route::get('/treandBook',[BookController::class,'trendBook']);



Route::controller(AuthController::class)->group(function () {
    Route::post('/signup', 'signup');
    Route::post('/login', 'login');
    Route::post('/verify_otp', 'verify_otp');
    Route::middleware('auth:sanctum')->post('/logout', 'logout');
    Route::middleware('auth:sanctum')->get('/user', 'user');
}); 


