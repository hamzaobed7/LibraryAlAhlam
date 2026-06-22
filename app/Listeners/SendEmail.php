<?php

namespace App\Listeners;

use App\Events\WaitBookEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */    public function handle(WaitBookEvent $event): void
    {
        $waitingList=$event->waitingList;
        $email=$waitingList->customer->user->email;
     Mail::raw(
    "Your reserved book '{$waitingList->book->title}' is now available.",
    function ($message) use ($email) {
        $message->to($email)
                ->subject('Book Available');
    }
);

}
}