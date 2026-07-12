<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RentalDueSoonNotification extends Notification
{
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Rental Reminder')
            ->line('Your rental period is about to expire.')
            ->action('View Rentals', url('/my-rentals'));
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Rental Reminder',
            'message' => 'Your rental will expire soon.',
        ];
    }
}
