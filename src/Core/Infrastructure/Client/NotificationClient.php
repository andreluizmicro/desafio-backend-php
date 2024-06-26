<?php

declare(strict_types=1);

namespace Core\Infrastructure\Client;

use GuzzleHttp\Client;

readonly class NotificationClient
{
    public function __construct(private Client $client)
    {
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
