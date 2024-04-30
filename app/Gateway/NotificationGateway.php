<?php

declare(strict_types=1);

namespace App\Gateway;

use App\Client\NotificationClient;
use Core\Domain\Exception\NotifyException;
use Core\Domain\Gateway\NotificationGatewayInterface;
use Illuminate\Http\Response;
use Throwable;

class NotificationGateway implements NotificationGatewayInterface
{
    public function __construct(
        private NotificationClient $client,
    ) {
    }

    public function notify()
    {
        try {
            $response = $this->client
                ->getClient()
                ->post('');

            if ($response->getStatusCode() !== Response::HTTP_OK) {
                throw new NotifyException();
            }

            $data = json_decode($response->getBody()->getContents(), true);

            if (! $data['message']) {
                throw new NotifyException();
            }
        } catch (Throwable) {
            throw new NotifyException();
        }
    }
}