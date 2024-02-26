<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class RefundMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $order;
    protected $refundData;
    protected $userDetails;

    /**
     * Create a new message instance.
     *
     * @param mixed $order
     * @param mixed $refundData
     */
    protected $title;

    /**
     * Create a new message instance.
     */

    public function __construct($order, $refundData, $userDetails)
    {
        $this->order = $order;
        $this->refundData = $refundData;
        $this->userDetails = $userDetails;
        $this->title            = env('APP_NAME').' : Refund Mail';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject($this->title)
            ->markdown('emails.refund', [
                'userDetails' => $this->userDetails,
                'order' => $this->order,
                'refundData' => $this->refundData
            ]);
    }
}
