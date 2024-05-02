<?php

declare(strict_types=1);

namespace Core\Infrastructure\Http\Controller;

use App\Http\Controllers\Controller;
use Core\Application\User\Create\CreateUserService;
use Core\Infrastructure\Http\Request\User\CreateUserFormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class UserController extends Controller
{
    public function __construct(
        private readonly CreateUserService $createUserService,
    ) {
    }

    public function create(CreateUserFormRequest $request): JsonResponse
    {
        try {
            $userId = $this->createUserService->execute($request->toDto());

            return response()->json($userId, Response::HTTP_CREATED);
        } catch (Throwable $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }
    }
}
