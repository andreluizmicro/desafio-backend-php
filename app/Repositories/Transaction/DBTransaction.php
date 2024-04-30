<?php

declare(strict_types=1);

namespace App\Repositories\Transaction;

use Core\Application\Interfaces\DBTransactionInterface;
use Illuminate\Support\Facades\DB;

class DBTransaction implements DBTransactionInterface
{
    public function __construct(
    ) {
        DB::beginTransaction();
    }

    public function commit(): void
    {
        DB::commit();
    }

    public function rollback(): void
    {
        DB::rollBack();
    }
}
