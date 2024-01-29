<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\UserGiftCard;

class GiftCardMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * User UserGiftCard.
     *
     * @var Usergiftcard
     */
    protected $usergiftcard;

    /** @var $title */
    protected $title;

    /**
     * Create a new message instance.
     */
    public function __construct(UserGiftCard $usergiftcard)
    {
        $this->usergiftcard     = $usergiftcard;
        $this->title            = env('APP_NAME').' : Gift Card Mail';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->to($this->usergiftcard->to_user)
            ->subject($this->title)
            ->markdown('emails.giftcard', [
                'code'  => $this->usergiftcard->code,
                'name'  => $this->usergiftcard->name
            ]);
    }
}
