<?php

declare(strict_types=1);

namespace App\Repositories\Mysql;

use App\Models\Transfer as TransferModel;
use Core\Domain\Entity\Transfer;
use Core\Domain\Repository\TransferRepositoryInterface;

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
