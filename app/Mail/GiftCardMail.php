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
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Gift Card Mail',
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'emails.giftcard',
    //     );
    // }

    // /**
    //  * Get the attachments for the message.
    //  *
    //  * @return array<int, \Illuminate\Mail\Mailables\Attachment>
    //  */
    // public function attachments(): array
    // {
    //     return [];
    // }

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
