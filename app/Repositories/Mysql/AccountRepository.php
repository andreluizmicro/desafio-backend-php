<?php

declare(strict_types=1);

namespace App\Repositories\Mysql;

use App\Models\Account as AccountModel;
use Core\Domain\Entity\Account;
use Core\Domain\Entity\User;
use Core\Domain\Exception\AccountException;
use Core\Domain\Exception\AlreadyExistsException;
use Core\Domain\Exception\CnpjException;
use Core\Domain\Exception\CpfException;
use Core\Domain\Exception\EmailException;
use Core\Domain\Exception\IdException;
use Core\Domain\Exception\NameException;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Exception\PasswordException;
use Core\Domain\Exception\PersonException;
use Core\Domain\Repository\AccountRepositoryInterface;
use Core\Domain\ValueObject\Id;
use Throwable;

class AccountRepository implements AccountRepositoryInterface
{
    public function __construct(
        private readonly AccountModel $model
    ) {
    }

    /**
     * @throws AlreadyExistsException
     */
    public function create(Account $account): int
    {
        try {
            return $this->model->create([
                'user_id' => $account->user->id->value,
                'balance' => $account->balance,
            ])->id;

        } catch (Throwable) {
            throw new AlreadyExistsException('Account Already exists');
        }
    }

    /**
     * @throws NotFoundException
     */
    public function findById(Id $id): Account
    {
        try {
            $data = $this->model
                ->with('user')
                ->find($id->value);

            return $this->modelToEntity($data);
        } catch (Throwable $th) {
            throw new NotFoundException('User not found');
        }
    }

    /**
     * @throws NotFoundException
     */
    public function findByUserId(Id $userId): Account
    {
        try {
            $data = $this->model
                ->with('user')
                ->where('user_id', $userId->value)
                ->get()
                ->first();

            return $this->modelToEntity($data);
        } catch (Throwable $th) {
            throw new NotFoundException('User not found');
        }
    }

    public function updateUserBalance(Account $account): void
    {
        $this->model->where('id', $account->id->value)->update(['balance' => $account->balance]);
    }

    public function existsById(Id $id): bool
    {
        return $this->model->where('user_id', $id->value)->exists();
    }

    /**
     * @throws PersonException
     * @throws PasswordException
     * @throws IdException
     * @throws NameException
     * @throws CpfException
     * @throws CnpjException
     * @throws EmailException
     * @throws AccountException
     */
    private function modelToEntity($data): Account
    {
        return new Account(
            user: User::createUserFactory($data['user']->toArray()),
            balance: $data['balance'],
            id: new Id($data['id']),
        );
    }
}