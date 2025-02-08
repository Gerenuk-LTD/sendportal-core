<?php

declare(strict_types=1);

namespace Sendportal\Base\Adapters;

use DomainException;
use Illuminate\Support\Arr;
use Postal\Client;
use Postal\Send\Message;
use SendGrid\Mail\TypeException;
use Sendportal\Base\Services\Messages\MessageTrackingOptions;
use Throwable;

class PostalAdapter extends BaseMailAdapter
{
    /**
     * @throws Throwable
     */
    public function send(string $fromEmail, string $fromName, string $toEmail, string $subject, MessageTrackingOptions $trackingOptions, string $content): string
    {
        $client = new Client('https://' . Arr::get($this->config, 'postal_host'), Arr::get($this->config, 'key'));

        $message = new Message();
        $message->to($toEmail);
        $message->from($fromName.' <'.$fromEmail.'>');
        $message->subject($subject);
        $message->htmlBody($content);
        $response = $client->send->message($message);

        return $this->resolveMessageId($response);
    }



    protected function resolveMessageId($response): string
    {
        foreach ($response->recipients() as $message) {
            return (string) $message->id;
        }

        throw new DomainException('Unable to resolve message ID');
    }
}
