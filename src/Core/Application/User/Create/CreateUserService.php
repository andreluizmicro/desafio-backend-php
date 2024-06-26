<?php

declare(strict_types=1);

namespace Core\Application\User\Create;

use Core\Domain\Entity\User;
use Core\Domain\Exception\UserException;
use Core\Domain\Repository\UserRepositoryInterface;
use Exception;
use InvalidArgumentException;

readonly class CreateUserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    /**
     * @throws UserException
     */
    public function execute(Input $input): Output
    {
        try {
            $user = User::createUserFactory($input->toArray());
            $id = $this->userRepository->create($user);

            return new Output(
                id: $id,
            );
        } catch (UserException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }
}
