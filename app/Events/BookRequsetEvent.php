<?php

namespace App\Events;

use App\Models\Book_request;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookRequsetEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Book_request $bookRequest;

    public function __construct(Book_request $bookRequest)
    {
        $this->bookRequest = $bookRequest;
    }

 
    public function broadcastOn(): array
    {
        return []; 
    }
}