<?php

declare(strict_types=1);

namespace Core\Infrastructure\Repository\Mysql;

use Core\Domain\Entity\Transfer;
use Core\Domain\Repository\TransferRepositoryInterface;
use Core\Infrastructure\Repository\Models\Transfer as TransferModel;

class TransferRepository implements TransferRepositoryInterface
{
    public function __construct(
        private TransferModel $model,
    ) {
    }

    public function create(Transfer $transfer): int
    {
        return $this->model->create([
            'payer_id' => $transfer->payer->id->value,
            'payee_id' => $transfer->payee->id->value,
            'value' => $transfer->value,
        ])->id;
    }
}
