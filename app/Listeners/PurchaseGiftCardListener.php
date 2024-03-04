<?php

namespace App\Listeners;

use App\Notifications\GiftCardNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\GiftCardEvent;

class PurchaseGiftCardListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(GiftCardEvent $event)
    {
        $senderName = $event->senderName;
        $event->usergiftcard->notify(new GiftCardNotification($senderName));
        // $event->usergiftcard->notify(new GiftCardNotification($event->usergiftcard));
    }
}
