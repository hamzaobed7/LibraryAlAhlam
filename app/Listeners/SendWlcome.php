<?php

namespace App\Listeners;

use App\Events\ActivateAccount;
use App\Notifications\WelcomeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendWlcome
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    
    public function handle(ActivateAccount $event): void
    {
        $user=$event->user;
        $user->notify(new WelcomeNotification());
    }
}
