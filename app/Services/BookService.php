<?php
namespace App\Services;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

 class BookService{
    public function getAllBooks()
    {
        return Book::with(['authors', 'category'])->get();
    }
    public function createBook(array $data, ?UploadedFile $cover = null)
    {
        if ($cover) {
            $fileName = $data['ISBN'] . "." . $cover->extension();
            $cover->storeAs("book_image", $fileName);
            $data['cover'] = $fileName;
        }

        return DB::transaction(function () use ($data) {
            $book = Book::create($data);
            $book->authors()->sync($data['authors']);
            return $book;
        });
    }

    public function updateBook(Book $book, array $data, ?UploadedFile $cover = null)
    {
        if ($cover) {
            if ($book->cover) {
                Storage::delete("book_image/" . $book->cover);
            }
            $fileName = $data['ISBN'] . "." . $cover->extension();
            $cover->storeAs("book_image", $fileName);
            $data['cover'] = $fileName;
        }

        return DB::transaction(function () use ($book, $data) {
            $book->update($data);
            if (isset($data['authors'])) {
                $book->authors()->sync($data['authors']);
            }
            return $book;
        });
    }

    public function deleteBook(Book $book){
    DB::transaction(function () use ($book) {
          
            if ($book->cover) {
                Storage::delete("book_image/" . $book->cover);
            }
            $book->authors()->detach();
            $book->delete();
        });

    }


    public function SearchBook(Request $request){
        $books = Book::query()
        ->with(['authors', 'category']);

    if ($request->filled('title')) {
        $books->where(
            'title',
            'LIKE',
            '%' . $request->title . '%'
        );
    }

    if ($request->filled('author')) {
        $books->whereHas('authors', function ($query) use ($request) {
            $query->where(
                'first_name',
                'LIKE',
                '%' . $request->author . '%'
            );
        });
    }

    if ($request->filled('category')) {
        $books->whereHas('category', function ($query) use ($request) {
            $query->where(
                'name',
                'LIKE',
                '%' . $request->category . '%'
            );
        });
    }

    if ($request->filled('from_date')) {
        $books->whereDate(
            'created_at',
            '>=',
            $request->from_date
        );
    }

    if ($request->filled('to_date')) {
        $books->whereDate(
            'created_at',
            '<=',
            $request->to_date
        );
    }
          

        return $books;

    }


    
}




