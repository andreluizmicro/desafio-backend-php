<?php

declare(strict_types=1);

namespace Core\Domain\Repository;

use Core\Domain\Entity\Transfer;

interface TransferRepositoryInterface
{
    public function create(Transfer $transfer): int;
}
