<?php

declare(strict_types=1);

namespace Core\Application\User\Create;

use Core\Domain\Entity\User;
use Core\Domain\Exception\CnpjException;
use Core\Domain\Exception\CpfException;
use Core\Domain\Exception\EmailException;
use Core\Domain\Exception\IdException;
use Core\Domain\Exception\NameException;
use Core\Domain\Exception\PasswordException;
use Core\Domain\Exception\UserException;
use Core\Domain\Repository\UserRepositoryInterface;

class CreateUserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    /**
     * @throws CnpjException
     * @throws PasswordException
     * @throws EmailException
     * @throws IdException
     * @throws NameException
     * @throws CpfException
     * @throws UserException
     */
    public function execute(Input $input): Output
    {
        $user = User::createUserFactory($input->toArray());

        $id = $this->userRepository->create($user);

        return new Output(
            id: $id,
        );
    }
}
