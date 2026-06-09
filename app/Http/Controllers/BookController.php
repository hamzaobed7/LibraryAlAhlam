<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\BookService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    protected $bookService;
    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function index(): JsonResponse
    {
        $books = $this->bookService->getAllBooks();
        if($books){
            return apiSuccess("تم جلب الكتب بنجاح", $books, 200);
        }
        else {
            return apiFail("Books not found",code:404);
        }
    }

    public function store(BookRequest $request): JsonResponse
    {
        $data = $request->validated(); 
        $book=$this->bookService->createBook($data, $request->file("cover"));
        return apiSuccess("تم إنشاء الكتاب بنجاح", $book->load(['authors', 'category']), 201);
    }

    public function show(Book $book): JsonResponse
    {
        $book->load(['authors', 'category']);
        return apiSuccess("تم جلب بيانات الكتاب بنجاح",  new BookResource($book), 200);
    }

    public function update(BookRequest $request, Book $book): JsonResponse
    {
        $data = $request->validated();
        $book=$this->bookService->updateBook($book, $data, $request->file("cover"));
        return apiSuccess("تم تحديث الكتاب بنجاح", $book->load(['authors', 'category']), 200);
    }

    public function destroy(Book $book): JsonResponse
    {
        $this->bookService->deleteBook($book);
        return apiSuccess("تم حذف الكتاب بنجاح", null, 200);
    }

    public function Search_Book(Request $request):JsonResponse
    {
        $book=$this->bookService->SearchBook($request)->get();
        if($book){
            return apiSuccess("The book is exist",$book,200);
        }
        return apiFail("Not Found",code:404);

    }
}