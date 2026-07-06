<?php

namespace App\Observers;

use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BookObserver
{

    public function created(Book $book): void
    {
        Cache::forget('books');
        Cache::forget('trendBook');
        Cache::forget('bookCount');
    }


    public function updated(Book $book): void
    {
        Cache::forget('books');
        Cache::forget('trendBook');
    }


    public function deleting(Book $book): void
    {
        if ($book->cover) {
            Storage::delete("book_image/" . $book->cover);
        }
        Cache::forget('books');
        Cache::forget('trendBook');
        Cache::forget('bookCount');
        Log::warning(
            "Book Deleted",
            [
                'book_id' => $book->id,
                'deleted_by' => Auth::user()->name,
            ]
        );
    }

    public function restored(Book $book): void {}


    public function forceDeleted(Book $book): void
    {
        if ($book->cover) {
            Storage::delete("book_image/" . $book->cover);
        }
    }
}
