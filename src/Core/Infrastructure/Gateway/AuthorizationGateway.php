<?php

declare(strict_types=1);

namespace Core\Infrastructure\Gateway;

use Core\Domain\Enum\Authorization;
use Core\Domain\Exception\AuthorizationException;
use Core\Domain\Gateway\AuthorizationGatewayInterface;
use Core\Infrastructure\Client\AuthorizationClient;
use Illuminate\Http\Response;
use Throwable;

readonly class AuthorizationGateway implements AuthorizationGatewayInterface
{
    public function __construct(
        private AuthorizationClient $client,
    ) {
    }

    /**
     * @throws AuthorizationException
     */
    public function authorizeTransfer(): void
    {
        try {
            $response = $this->client
                ->getClient()
                ->post('');

            if ($response->getStatusCode() !== Response::HTTP_OK) {
                throw new AuthorizationException();
            }

            $data = json_decode($response->getBody()->getContents(), true);

            if (! $this->isTransferAuthorized($data)) {
                throw new AuthorizationException();
            }

        } catch (Throwable $th) {
            throw new AuthorizationException();
        }
    }

    private function isTransferAuthorized(array $data): bool
    {
        return $data['message'] === Authorization::AUTHORIZATION_TRANSFER->value;
    }
}
