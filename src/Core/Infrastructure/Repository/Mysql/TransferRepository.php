<?php

declare(strict_types=1);

namespace Core\Infrastructure\Repository\Mysql;

use Core\Domain\Entity\Transfer;
use Core\Domain\Repository\TransferRepositoryInterface;
use Core\Infrastructure\Repository\Models\Transfer as TransferModel;
use Throwable;

class TransferRepository implements TransferRepositoryInterface
{
    public function __construct(
        private TransferModel $model,
    ) {
    }

    public function create(Transfer $transfer): int
    {
        try {
            return $this->model->create([
                'payer_id' => $transfer->getPayer()->getId()->value,
                'payee_id' => $transfer->getPayee()->getId()->value,
                'value' => $transfer->getValue(),
            ])->id;
        } catch (Throwable $th) {
            dd($th);
        }
    }
}
