<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * User order.
     *
     * @var $order
     */
    protected $order;
    protected $cardDetails;

    /** @var $title */
    protected $title;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order,$cardDetails)
    {
        $this->order     = $order;
        $this->cardDetails     = $cardDetails;
        $this->title     = env('APP_NAME').' : Invoice for order #'.$order->id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $filename       = 'invoice_'.$this->order->id.'.pdf';
        
        return $this
            ->subject($this->title)
            ->markdown('emails.invoice', [
                'name'  => $this->order->user->name,
                'order' => $this->order,
                'cardDetails' =>  $this->cardDetails
            ])
            ->attach(storage_path("app/public/order_pdf/{$filename}"), ['mime' => 'application/pdf']);
    }
}