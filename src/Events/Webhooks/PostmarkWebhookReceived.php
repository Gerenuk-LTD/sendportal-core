<?php

namespace Sendportal\Base\Events\Webhooks;

class PostmarkWebhookReceived
{
    public array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }
}
