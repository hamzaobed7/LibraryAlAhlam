<?php

namespace App\Observers;

use App\Events\BookAvailableEvent;
use App\Events\WaitBookEvent;
use App\Models\Remove_Frome_remaining;
use App\Models\WatingList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OpreationOnStockObserver
{

    public function created(Remove_Frome_remaining $record): void
    {
        $user = Auth::user();
        if (!$record->remove_from_remaining) {
            return;
        }

        $book = $record->book;

        if (!$book) {
            return;
        }

        if ($record->type === 'add') {
            DB::transaction(function () use ($record, $book) {
                $wasOutOfStock = $book->stock == 0;
                $book->increment('stock', $record->quantity);
                $book->increment('total_copies', $record->quantity);
                if ($wasOutOfStock) {
                    BookAvailableEvent::dispatch($book);
                }
            });
        }

        if ($record->type === 'destroy') {
            $book->decrement('stock', $record->quantity);
        }
        Log::info('User operation on stock:', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'book_id' => $book->id,
            'book_title' => $book->title,
            'operation_type' => $record->type,
            'quantity' => $record->quantity,

        ]);
    }

    /**
     * Handle the Remove_Frome_remaining "updated" event.
     */
    public function updated(Remove_Frome_remaining $remove_Frome_remaining): void
    {
        //
    }

    /**
     * Handle the Remove_Frome_remaining "deleted" event.
     */
    public function deleted(Remove_Frome_remaining $remove_Frome_remaining): void
    {
        //
    }

    /**
     * Handle the Remove_Frome_remaining "restored" event.
     */
    public function restored(Remove_Frome_remaining $remove_Frome_remaining): void
    {
        //
    }

    /**
     * Handle the Remove_Frome_remaining "force deleted" event.
     */
    public function forceDeleted(Remove_Frome_remaining $remove_Frome_remaining): void
    {
        //
    }
}
