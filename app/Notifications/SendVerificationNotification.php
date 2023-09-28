<?php

namespace App\Notifications;

use App\Mail\VerificationMail;
use App\Models\User;
use Illuminate\Notifications\Notification;

class SendVerificationNotification extends Notification
{
    /** @var \App\Models\User */
    protected $user;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\User $user
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
        return new VerificationMail($this->user);
    }
}
