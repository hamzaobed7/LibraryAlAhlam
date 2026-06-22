<?php

namespace App\Http\Controllers;

use App\Events\BookRequsetEvent;
use App\Events\BookResulteEvent;
use App\Http\Requests\Book_requestRequest;
use App\Models\Book;
use App\Models\Book_request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Book_requestController extends Controller
{
    
    public function index():JsonResponse
    {
        $bookRequest=Book_request::with('customer')->get();
        if($bookRequest){
            return apiSuccess('All Book reqest',$bookRequest,201);
        }
        else{
            return apiFail('Not Found');
        }
    }
    
public function store(Book_requestRequest $request): JsonResponse
{
    $data = $request->validated();
    
    $user = Auth::user();
    if (!$user || !$user->customer) {
        return apiFail("User does not have a customer profile account.", null, 403);
    }
    
    $customerId = $user->customer->id;
    $book = Book::where('title', 'LIKE', "%{$data['book_title']}%")->first();
    if ($book) {
        return apiFail("The book already exists", $book, 422); 
    }
    
    $oldRequest = Book_request::where('book_title', 'LIKE', "%{$data['book_title']}%")
        ->where('customer_id', $customerId)
        ->first();
        
    if ($oldRequest) {
        return apiFail("You have already requested this book before.", null, 400);
    }
    $bookRequest = Book_request::create([
        'book_title'  => $data['book_title'],
        'author_name' => $data['author_name'],
        'customer_id' => $customerId
    ]);

    BookRequsetEvent::dispatch($bookRequest);

    return apiSuccess('The request has been saved successfully', $bookRequest, 200);
}

  
   public function show(Book_request $book_request): JsonResponse
{
    if($book_request){
        $book_request->load('customer');
    return apiSuccess("this book", $book_request, 200); 
    }
    return apiFail("the request not found",null,404);
}

   

public function updateStatus(Request $request, Book_request $book_request)
{
    $request->validate([
        'status' => 'required|string'
    ]);
    $book_request->status = $request->status;
    $book_request->save();
    BookResulteEvent::dispatch($book_request);
    return apiSuccess("the Request is updated", $book_request, 200);
}



    
    public function destroy(Book_request $book_request)
    {
        $book_request->delete();
        return apiSuccess("the Reqest is deleted");
    }
}
