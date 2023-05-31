<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;

class CustomVerifyEmailNotification extends VerifyEmail
{
    protected function verificationUrl($notifiable)
    {
        $verify = $notifiable->verifies()->create();

        $query = http_build_query([
            'email' => $notifiable->email,
            'code' => $verify->code,
        ]);

        return config('frontend.url').'/verify-email?'.$query;
    }
}
