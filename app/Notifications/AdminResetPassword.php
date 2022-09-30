<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AdminResetPassword extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a new notification instance.
     *
     * @param $token
     */
    public function __construct($token, $email, $name, $id)
    {
        $this->token = $token;
        $this->email = $email;
        $this->name = $name;
        $this->id = $id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $passwordResetUrl = url('admin/password/reset') . '/' . $this->token;
        return (new MailMessage)
			->action('Reset Password', $passwordResetUrl)
            ->line($this->email)
            ->line($this->name)
            ->line($this->id)
            ->line('admin')
            ->line($this->token);
    }
}
