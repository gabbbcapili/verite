<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Helpers\Helper;
use Illuminate\Support\HtmlString;

class ResetPassword extends Notification
{
    public $token;
    public $user;

    public function __construct($token, $user)
    {
        $this->token = $token;
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Reset Password')
            ->greeting('Hello ' . $this->user->fullName)
            ->line(new HtmlString(Helper::settings()->user_reset))
            ->action('Reset Password', url('password/reset', $this->token))
            ->line('This password reset link will expire in 60 minutes.');
    }
}
