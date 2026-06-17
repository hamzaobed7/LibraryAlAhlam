<?php

namespace App\Http\Controllers;

use App\Http\Requests\Book_requestRequest;
use App\Models\Book;
use App\Models\Book_request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Book_requestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():JsonResponse
    {
        $bookRequest=Book_request::all();
        if($bookRequest){
            return apiSuccess('All Book reqest',$bookRequest,201);
        }
        else{
            return apiFail('Not Found');
        }
    }

    public function store(Book_requestRequest $request):JsonResponse
    {
        $data=$request->validated();
        $book=Book::where('title',"LIKE","%{$data['book_title']}%")->first();
        if($book){
            return apiFail("the book is already exist",$book);
        }
        $BookRequest=Book_request::create($data);
        return apiSuccess('the request is saved');
    }

  
    public function show(Book_request $book_request):JsonResponse
    {
        if($book_request){
            return apiSuccess("this book",$book_request,201);
        }
        return apiFail("The book is not Found",code:404);
    }

   
    public function update(Book_requestRequest $request, Book_request $book_request)
    {
        $data=$request->validated();
        $book_request->update($data);
        return apiSuccess("the Request is updated");
    }

    
    public function destroy(Book_request $book_request)
    {
        $book_request->delete();
        return apiSuccess("the Reqest is deleted");
    }
}
