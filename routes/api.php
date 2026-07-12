<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Services\PaypalService;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\Book_requestController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Remove_Frome_remainingController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WatingListController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('categories', CategoryController::class)->only('index', 'show');
Route::apiResource("authors", AuthorController::class)->only('index', 'show');
Route::apiResource("books", BookController::class)->only('index', 'show', 'Search_Book');
Route::apiResource('/book_request', Book_requestController::class)->only('index', 'show');
Route::get('/requests', [WatingListController::class, 'index']);
Route::get('/requests/{id}', [WatingListController::class, 'show']);
Route::get('/customers', [CustomerController::class, 'index']);
Route::get('/ShowItems/{bill}', [CustomerController::class, 'ShowItems']);


Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('categories', CategoryController::class)->except('index', 'show');
    Route::apiResource("authors", AuthorController::class)->except('index', 'show');
    Route::apiResource("books", BookController::class)->except('index', 'show');
    Route::apiResource("remove_frome_remaining", Remove_Frome_remainingController::class);
    Route::apiResource('/bill', BillController::class);
    Route::get('RentalsInfo',[RentalController::class, 'index']);
    Route::patch('/admin/profile', [UserController::class, 'updateAdmin']);
    Route::get('/customers/{customer}', [CustomerController::class, 'show']);
    Route::patch('/upstatus/{book_request}', [Book_requestController::class, 'updateStatus']);
    Route::prefix('/deletemulti')->group(function () {
        Route::delete('/author', [AuthorController::class, 'DeleteManyAuthor']);
        Route::delete('/books', [BookController::class, 'DeleteManyBook']);
    });
});

Route::middleware(['auth:sanctum', 'role:customer'])->group(function () {
    Route::apiResource('/book_request', Book_requestController::class)->except('index', 'show');
    Route::apiResource('/bill', BillController::class)->except('index', "show");
    Route::put('/UpdateProfile', [CustomerController::class, 'update']);
    Route::get('/MyProfile', [CustomerController::class, 'profile']);
    Route::patch("/statusToCancel/{bill}", [BillController::class, 'updateStatusToCancel']);
    Route::get('/showMyBill', [CustomerController::class, 'showMyBill']);
    Route::post('/payment', [PaymentController::class, 'store']);
    Route::put('/payment/capture', [PaymentController::class, 'capturePayment']); 
    Route::get('MyBook',[RentalController::class, 'MyRentalsBook']);
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/', [CartController::class, 'store']);
        Route::delete('/{id}', [CartController::class, 'destroy']);
        Route::get('/count', [CartController::class, 'CountCart']);
    });
    Route::delete('/deleteBill/{bill}', [BillController::class, 'destroy']);
    Route::post('/request-list', [WatingListController::class, 'store']);
    Route::get('showinvoice/{bill}', [CustomerController::class, 'showBill']);
    Route::get('/requsts', [CustomerController::class, 'AllRequest']);
});


Route::prefix('/Counts')->group(function () {
    Route::get('/Books', [BookController::class, 'bookCount']);
    Route::get('/category', [CategoryController::class, 'CategoryCount']);
    Route::get('/author', [AuthorController::class, 'AuthorCount']);
    Route::get('/AddStock', [Remove_Frome_remainingController::class, 'theOperationAdd']);
    Route::get('/hasNobook', [AuthorController::class, 'HasNoBook']);
    Route::get('users', [CustomerController::class, 'CustomrtCount']);
    Route::get('rentals', [RentalController::class, 'CountOfRentals']);
    Route::get('CustomerHasRental',[RentalController::class, 'CountOfUserRentals']);
});


// Route::get('/paypal/token', function (PaypalService $paypal) {
//     return response()->json([
//         'token' => $paypal->getAccessToken()
//     ]);
// });



Route::get('book-search', [BookController::class, 'Search_Book']);


//for return Category that have books
Route::get('categoryhasbooks', [CategoryController::class, 'HasBook']);



//for Landing page
Route::get('/treandBook', [BookController::class, 'trendBook']);



Route::controller(AuthController::class)->group(function () {
    Route::post('/signup', 'signup');
    Route::post('/login', 'login');
    Route::post('/verify_otp', 'verify_otp');
    Route::middleware('auth:sanctum')->post('/logout', 'logout');
    Route::middleware('auth:sanctum')->get('/user', 'user');
});
