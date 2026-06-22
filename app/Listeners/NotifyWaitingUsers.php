<?php

namespace App\Listeners;

use App\Events\BookAvailableEvent;
use App\Events\WaitBookEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\WatingList;

class NotifyWaitingUsers
{
    public function handle(BookAvailableEvent $event): void
    {
        $book = $event->book;

        $waitingLists = WatingList::where('book_id', $book->id)
            ->oldest()
            ->get();

            foreach ($waitingLists as $waiting) {
               WaitBookEvent::dispatch($waiting);
               $waiting->delete();
               }
    }
}

