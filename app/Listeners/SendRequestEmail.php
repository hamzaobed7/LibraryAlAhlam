<?php

namespace App\Listeners;

use App\Events\BookRequsetEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendRequestEmail
{
   
    public function __construct()
    {
        
    }

  
    public function handle(BookRequsetEvent $event): void
    {
        
        $bookRequest = $event->bookRequest;
        $email = $bookRequest->customer->user->email ?? null;

        if ($email) {
            $bookTitle = $bookRequest->book_title;
            
            Mail::raw("Your request for the book '{$bookTitle}' has been received and we will process it as soon as possible.", function ($message) use ($email) {
                $message->to($email)->subject('Request Received Successfully');
            });
        }
    }
}