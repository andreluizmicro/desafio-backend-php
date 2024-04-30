<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserFormRequest;
use Core\Application\User\Create\CreateUserService;
use Core\Domain\Exception\UserException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(
        private CreateUserService $createUserService,
    ) {
    }

    public function create(CreateUserFormRequest $request): JsonResponse
    {
        try {
            $userId = $this->createUserService->execute($request->toDto());

            return response()->json($userId, Response::HTTP_CREATED);
        } catch (UserException $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_CONFLICT);
        }
    }
}
