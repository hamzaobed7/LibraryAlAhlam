<?php

namespace App\Listeners;

use App\Events\BookResulteEvent;
use App\Notifications\ResultEmailNoti;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendResulteEmail
{

    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(BookResulteEvent $event): void
    {
        $bookRequest = $event->book_request;
        $status = $event->book_request->status;
        $user = $bookRequest->customer->user ?? null;
        $name = $bookRequest->customer->name;
        $bookTitle = $bookRequest->book_title;
        $user->notify(new ResultEmailNoti(  $status, $bookTitle,$name));
    }
}
