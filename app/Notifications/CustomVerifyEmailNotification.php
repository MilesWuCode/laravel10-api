<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;

class CustomVerifyEmailNotification extends Notification
{
    use Queueable;

    public string $email;

    public string $code;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $email, string $code)
    {
        $this->email = $email;

        $this->code = $code;

        Cache::put('verify_email_notification.email.'.$email, $code, $seconds = 3600);
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
        $query = http_build_query([
            'email' => $this->email,
            'code' => $this->code,
        ]);

        $url = config('frontend.url').'/verify-email?'.$query;

        return (new MailMessage)
            ->subject(Lang::get('Verify Email Address'))
            ->line(Lang::get('Please click the button below to verify your email address.'))
            ->action(Lang::get('Verify Email Address'), $url)
            ->line(Lang::get('If you did not create an account, no further action is required.'));
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
