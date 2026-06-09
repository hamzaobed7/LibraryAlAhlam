<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Remove_Frome_remainingController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('categories',CategoryController::class);
Route::apiResource("authors",AuthorController::class);
Route::apiResource("books",BookController::class);
Route::get('book-search',[BookController::class,'Search_Book']);
Route::apiResource("remove_frome_remaining",Remove_Frome_remainingController::class);
Route::controller(AuthController::class)->group(function () {
    Route::post('/signup', 'signup');
    Route::post('/login', 'login');
    Route::post('/send-otp', 'send_otp');
    Route::post('/verify-otp', 'verify_otp');
    Route::middleware('auth:sanctum')->post('/logout', 'logout');
    Route::middleware('auth:sanctum')->get('/user', 'user');
}); 
