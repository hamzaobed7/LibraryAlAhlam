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


   

    
}




