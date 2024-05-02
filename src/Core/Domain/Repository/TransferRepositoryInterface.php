<?php

declare(strict_types=1);

namespace Core\Domain\Repository;

use Core\Domain\Entity\Transfer;
use Core\Domain\Exception\TransferException;

interface TransferRepositoryInterface
{
    /**
     * @throws TransferException
     */
    public function create(Transfer $transfer): int;
}
