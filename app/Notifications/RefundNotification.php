<?php

namespace App\Notifications;

use App\Mail\RefundMail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;


class RefundNotification extends Notification
{
    use Queueable;
    protected $order;
    protected $refundData;

    /**
     * Create a new notification instance.
     */
    public function __construct($order, $refundData)
    {
        $this->order = $order;
        $this->refundData = $refundData;
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

    public function toMail(object $notifiable): RefundMail
    {  
        $userDetails = $notifiable;
        return (new RefundMail($this->order, $this->refundData,$userDetails))
            ->to($notifiable->email)
            ->with(['order' => $this->order, 'refundData' => $this->refundData]);
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
