<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GiftCardEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \App\Models\UserGiftCard
     */
    public $usergiftcard;
    public $senderName;

    /**
     * Create a new event instance.
     */
    public function __construct($usergiftcard,$senderName)
    {
        $this->usergiftcard = $usergiftcard;
        $this->senderName = $senderName;
    }
}
