<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * User Model.
     *
     * @var User
     */
    protected $user;

    /** @var $title */
    protected $title;

    /**
     * Create a new message instance.
     *
     * @param User $user
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user     = $user;
        $this->title    = env('APP_NAME').' : Verification Email';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $token = $this->user->id.sha1(time());

        // update user for verification code
        $this->user->update(['verification_code' => $token]);

        return $this
            ->to($this->user->email)
            ->subject($this->title)
            ->markdown('emails.verification', [
                'user'  => $this->user,
                'url'   => route('auth.verify-email', ['token' => $token])
            ]);
    }
}
