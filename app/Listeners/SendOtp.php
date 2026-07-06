<?php

namespace App\Listeners;

use App\Events\CreateOtp;
use App\Notifications\OtpNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOtp
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
     */
    public function handle(CreateOtp $event): void
    {
        $user = $event->user;
        $user->notify(new OtpNotification($event->otp_code));
    }
}
