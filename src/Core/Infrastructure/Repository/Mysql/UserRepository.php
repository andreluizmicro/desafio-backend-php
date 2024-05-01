<?php

declare(strict_types=1);

namespace Core\Infrastructure\Repository\Mysql;

use Core\Domain\Entity\User;
use Core\Domain\Exception\CnpjException;
use Core\Domain\Exception\CpfException;
use Core\Domain\Exception\EmailException;
use Core\Domain\Exception\IdException;
use Core\Domain\Exception\NameException;
use Core\Domain\Exception\PasswordException;
use Core\Domain\Exception\PersonException;
use Core\Domain\Exception\UserException;
use Core\Domain\Repository\UserRepositoryInterface;
use Core\Domain\ValueObject\Id;
use Core\Infrastructure\Repository\Models\User as UserModel;
use Exception;
use Illuminate\Database\UniqueConstraintViolationException;
use Throwable;

readonly class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private UserModel $model
    ) {
    }

    /**
     * @throws UserException
     * @throws Exception
     */
    public function create(User $user): int
    {
        try {
            return $this->model->create(
                $user->toArray()
            )->id;
        } catch (UniqueConstraintViolationException $exception) {
            throw UserException::userAlreadyExists();
        } catch (Throwable $exception) {
            throw new UserException($exception->getMessage());
        }
    }

    /**
     * @throws EmailException
     * @throws CnpjException
     * @throws PasswordException
     * @throws IdException
     * @throws CpfException
     * @throws NameException
     * @throws PersonException
     * @throws UserException
     */
    public function findById(Id $id): ?User
    {
        try {
            $user = $this->model
                ->where('id', $id->value)
                ->get()->first();

            return User::createUserFactory($user->toArray());
        } catch (Throwable) {
            throw UserException::userNotFound($id->value);
        }
    }
}
