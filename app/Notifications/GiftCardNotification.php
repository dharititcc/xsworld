<?php

namespace App\Notifications;

use App\Mail\GiftCardMail;
use App\Models\UserGiftCard;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GiftCardNotification extends Notification
{
    // use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $senderName;

    public function __construct($senderName)
    {
        $this->senderName = $senderName;
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
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(object $notifiable)
    {
        // return new GiftCardMail($notifiable);
        return (new GiftCardMail($notifiable, $this->senderName));
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
