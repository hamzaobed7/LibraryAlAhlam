<?php

namespace App\Listeners;

use App\Events\WaitBookEvent;
use App\Notifications\AvailableEmailNoti;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmail
{

    public function __construct()
    {
        //
    }

    public function handle(WaitBookEvent $event): void
    {
        $waitingList = $event->waitingList;
        $title = $waitingList->book->title;
        $user = $waitingList->customer->user;
        $user->notify(new AvailableEmailNoti($title));
    }
}
