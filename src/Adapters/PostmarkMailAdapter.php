<?php

declare(strict_types=1);

namespace Sendportal\Base\Adapters;

use Illuminate\Support\Arr;
use Postmark\Models\PostmarkException;
use Postmark\Models\PostmarkResponse;
use Postmark\PostmarkClient;
use Sendportal\Base\Services\Messages\MessageTrackingOptions;

class PostmarkMailAdapter extends BaseMailAdapter
{
    protected PostMarkClient $client;

    /**
     * @throws PostmarkException
     */
    public function send(string $fromEmail, string $fromName, string $toEmail, string $subject, MessageTrackingOptions $trackingOptions, string $content): string
    {
        $result = $this->resolveClient()->sendEmail(
            "{$fromName} <{$fromEmail}>",
            $toEmail,
            $subject,
            $content,
            null,
            null,
            $trackingOptions->isOpenTracking(),
            null,
            null,
            null,
            null,
            null,
            $trackingOptions->isClickTracking() ? 'HtmlAndText' : 'None',
            null,
            Arr::get($this->config, 'message_stream')
        );

        return $this->resolveMessageId($result);
    }

    protected function resolveClient(): PostmarkClient
    {
        if ($this->client) {
            return $this->client;
        }

        $this->client = new PostmarkClient(Arr::get($this->config, 'key'));

        return $this->client;
    }

    protected function resolveMessageId(PostmarkResponse $result): string
    {
        return $result->getMessageID();
    }
}
