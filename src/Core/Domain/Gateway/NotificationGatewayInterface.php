<?php

declare(strict_types=1);

namespace Core\Domain\Gateway;

use Core\Domain\Exception\NotifyException;

interface NotificationGatewayInterface
{
    /**
     * @throws NotifyException
     */
    public function notify();
}
