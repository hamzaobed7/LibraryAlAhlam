<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(): JsonResponse
    {
        $books = Book::with(['authors', 'category'])->paginate(15);
        return apiSuccess("تم جلب الكتب بنجاح", $books, 200);
    }

    public function store(BookRequest $request): JsonResponse
    {
        $data = $request->validated(); 
        if ($request->hasFile("cover")) {
            $fileName = $request->ISBN .".". $request->file("cover")->extension();
            $request->file("cover")->storeAs("book_image", $fileName);
            $data['cover'] = $fileName;
        }

        $book = DB::transaction(function () use ($data) {
            $book = Book::create($data);
            $book->authors()->sync($data['authors']);
            return apiFail("حدث خطأ أثناء إنشاء الكتاب", null, 500);
        });

        return apiSuccess("تم إنشاء الكتاب بنجاح", $book->load(['authors', 'category']), 201);
    }

    public function show(Book $book): JsonResponse
    {
        return apiSuccess("تم جلب بيانات الكتاب بنجاح", $book->load(['authors', 'category']), 200);
    }

    public function update(BookRequest $request, Book $book): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile("cover")) {
            if ($book->cover) {
                Storage::delete("book_image/" . $book->cover);
            }
            $fileName = $data['ISBN'] . "." . $request->file("cover")->extension();
            $request->file("cover")->storeAs("book_image", $fileName);
            $data['cover'] = $fileName;
        }

        DB::transaction(function () use ($book, $data) {
            $book->update($data);
            if (isset($data['authors'])) {
                $book->authors()->sync($data['authors']);
            }
        });

        return apiSuccess("تم تحديث الكتاب بنجاح", $book->load(['authors', 'category']), 200);
    }

    public function destroy(Book $book): JsonResponse
    {
        DB::transaction(function () use ($book) {
          
            if ($book->cover) {
                Storage::delete("book_image/" . $book->cover);
            }
            
            $book->authors()->detach();
            $book->delete();
        });

        return apiSuccess("تم حذف الكتاب بنجاح", null, 200);
    }
}