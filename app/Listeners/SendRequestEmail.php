<?php

namespace App\Listeners;

use App\Events\BookRequsetEvent;
use App\Notifications\RequestEmailNoti;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendRequestEmail
{

    public function __construct() {}


    public function handle(BookRequsetEvent $event): void
    {

        $bookRequest = $event->bookRequest;
        $user = $bookRequest->customer->user ?? null;
        $bookTitle = $bookRequest->book_title;
        $user->notify(new RequestEmailNoti(  $bookTitle));
    }
}
