<?php

namespace Sendportal\Base\Events\Webhooks;

class SendgridWebhookReceived
{
    public array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }
}
