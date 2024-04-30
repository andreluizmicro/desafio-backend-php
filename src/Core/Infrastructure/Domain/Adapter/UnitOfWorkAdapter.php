<?php

declare(strict_types=1);

namespace Core\Infrastructure\Domain\Adapter;

use Core\Domain\Adapter\UnitOfWorkAdapterInterface;
use Illuminate\Support\Facades\DB;
use Throwable;

class UnitOfWorkAdapter implements UnitOfWorkAdapterInterface
{
    public const DATABASE_NAME = 'mysql';

    private int $level = 0;

    /**
     * @throws Throwable
     */
    public function begin(): void
    {
        $this->level++;

        if ($this->level === 1) {
            DB::connection(self::DATABASE_NAME)->beginTransaction();
        }
    }

    /**
     * @throws Throwable
     */
    public function commit(): void
    {
        if ($this->level === 1) {
            DB::connection(self::DATABASE_NAME)->commit();
        }

        if ($this->level > 0) {
            $this->level--;
        }
    }

    /**
     * @throws Throwable
     */
    public function rollback(): void
    {
        if ($this->level === 1) {
            DB::connection(self::DATABASE_NAME)->rollback();
        }

        if ($this->level > 0) {
            $this->level--;
        }
    }
}
