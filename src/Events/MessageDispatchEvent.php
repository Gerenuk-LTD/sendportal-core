<?php

namespace Sendportal\Base\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Sendportal\Base\Models\Message;

class MessageDispatchEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public Message $message;

    /**
     * MessageDispatchEvent constructor
     *
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn(): Channel|array
    {
        return new PrivateChannel('channel-name');
    }
}
