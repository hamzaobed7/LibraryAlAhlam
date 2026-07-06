<?php

namespace App\Listeners;

use App\Events\AuthonticationEvent;
use Illuminate\Support\Facades\Log;
class LogInformation
{
   
    public function __construct()
    {
        //
    }

    
    public function handle(AuthonticationEvent $event): void
    {
        $user = $event->user;
        Log::info('User authenticated:', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'type' => $user->type,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]);
    }
}
