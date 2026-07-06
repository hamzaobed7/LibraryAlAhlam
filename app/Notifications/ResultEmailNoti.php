<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResultEmailNoti extends Notification
{
    use Queueable;
    
    protected $status;
    protected $bookTitle;
    protected $name;
    public function __construct( string $status,string $bookTitle, string $name)
    {
        $this->status=$status;
        $this->bookTitle=$bookTitle;
        $this->name=$name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
        ->subject("Request Received Successfully")
        ->greeting("Hi ,{$this->name}")
            ->line("Your request for the book '{$this->bookTitle}'")
            ->line("is {$this->status}")
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
