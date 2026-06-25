<?php

namespace App\Observers;

use App\Models\Author;
use Illuminate\Support\Facades\Cache;

class AuthorObserver
{
    /**
     * Handle the Author "created" event.
     */
    public function created(Author $author): void
    {
        Cache::forget('authors');
    }

    /**
     * Handle the Author "updated" event.
     */
    public function updated(Author $author): void
    {
         Cache::forget('authors');
    }

    /**
     * Handle the Author "deleted" event.
     */
    public function deleted(Author $author): void
    {
         Cache::forget('authors');
    }

    /**
     * Handle the Author "restored" event.
     */
    public function restored(Author $author): void
    {
        //
    }

    /**
     * Handle the Author "force deleted" event.
     */
    public function forceDeleted(Author $author): void
    {
        //
    }
}
