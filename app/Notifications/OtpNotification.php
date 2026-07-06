<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification
{
    use Queueable;

   protected $otp_code;
    public function __construct($otp_code)
    {
        $this->otp_code=$otp_code;
    }


    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
{
    return (new MailMessage)
        ->subject("رمز التحقق الخاص بك")
        ->greeting("مرحباً، $notifiable->name")
        ->line("لقد طلبت رمز التحقق (OTP) لإتمام عملية التسجيل.")
        ->action($this->otp_code, '#') 
        ->line('هذا الرمز صالح لمدة 5 دقائق فقط. يرجى عدم مشاركته مع أي أحد.')
        ->salutation("مع تحياتنا،\nفريق عمل المكتية");
}

   
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
