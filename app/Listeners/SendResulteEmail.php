<?php

namespace App\Listeners;

use App\Events\BookResulteEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendResulteEmail
{
   
    public function __construct()
    {
    
    }

    /**
     * Handle the event.
     */
    public function handle(BookResulteEvent $event): void
    {
        $bookRequest = $event->book_request;
        $email = $bookRequest->customer->user->email ?? null;

        if ($email) {
            $bookTitle = $bookRequest->book_title;
            
            Mail::raw("Your request for the book '{$bookTitle}' is '{$bookRequest->status}'  .", function ($message) use ($email) {
                $message->to($email)->subject('Request Received Successfully');
            });
        }
        
    }
}
