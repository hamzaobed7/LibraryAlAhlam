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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

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
        if ($books) {
            return apiSuccess("تم جلب الكتب بنجاح", $books, 200);
        } else {
            return apiFail("Books not found", code: 404);
        }
    }

    public function store(BookRequest $request): JsonResponse
    {
        $data = $request->validated();
        $book = $this->bookService->createBook($data, $request->file("cover"));
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
        $book = $this->bookService->updateBook($book, $data, $request->file("cover"));
        return apiSuccess("تم تحديث الكتاب بنجاح", $book->load(['authors', 'category']), 200);
    }

    public function destroy(Book $book): JsonResponse
    {  
        if(!Auth::user()->can('delete',$book)){
            return apiFail("ليس لديك الصلاحية لحذف هذا الكتاب");
        }
        $this->bookService->deleteBook($book);
        return apiSuccess("تم حذف الكتاب بنجاح", null, 200);
    }

    public function Search_Book(Request $request)
    {
        $filters = $request->only(['title', 'author', 'category', 'from_date', 'to_date']);
        $books = Book::with(['authors', 'category'])
            ->filter($filters)
            ->get();

        return apiSuccess(
            'تم جلب الكتب بنجاح',
            BookResource::collection($books),
            200
        );
    }
    public function bookCount()
    { 
        return Cache::remember('bookCount',3600,fn()=>Book::all()->count());
    }

    public function trendBook()
    {
        return Cache::remember('trendBook',3600,fn()=>Book::with('category')->take(6)->get());
    }

    public function DeleteManyBook(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:books,id'
        ]);

        $ids = $request->input('ids');
        $books = Book::whereIn('id', $ids)->get();
        foreach ($books as $book) {
            if (!Auth::user()->can('delete', $book))
                return apiFail("ليس لديك الصلاحيات لحذف هذا الكتاب", Auth::user()->type, code: 403);
        }
        Book::whereIn('id', $ids)->delete();

        return apiSuccess("تم الحذف بنجاح", code: 200);
    }
}
