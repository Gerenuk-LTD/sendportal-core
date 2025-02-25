<?php

namespace Sendportal\Base\Events\Webhooks;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MailgunWebhookReceived
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }
}
