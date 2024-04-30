<?php

declare(strict_types=1);

namespace Core\Domain\Gateway;

use Illuminate\Auth\Access\AuthorizationException;

interface AuthorizationGatewayInterface
{
    /**
     * @throws AuthorizationException
     */
    public function authorizeTransfer(): void;
}
