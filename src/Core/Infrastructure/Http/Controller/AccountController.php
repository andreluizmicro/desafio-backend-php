<?php

declare(strict_types=1);

namespace Core\Infrastructure\Http\Controller;

use App\Http\Controllers\Controller;
use Core\Application\Account\Create\CreateAccountService;
use Core\Application\Account\Deposit\DepositAccountService;
use Core\Domain\Exception\UserException;
use Core\Infrastructure\Http\Request\Account\CreateAccountFormRequest;
use Core\Infrastructure\Http\Request\Account\DepositAccountFormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class AccountController extends Controller
{
    public function __construct(
        private readonly CreateAccountService $createAccountService,
        private readonly DepositAccountService $depositAccountService,
    ) {
    }

    public function create(CreateAccountFormRequest $request): JsonResponse
    {
        try {
            $accountIdCreated = $this->createAccountService->execute($request->toDto());

            return response()->json(['account_id' => $accountIdCreated->accountId], Response::HTTP_CREATED);
        } catch (UserException $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (Throwable $th) {
            return response()->json(['message' => $th->getMessage()], $th->getCode());
        }
    }

    public function deposit(DepositAccountFormRequest $request): JsonResponse
    {
        try {
            $this->depositAccountService->execute($request->toDto());

            return response()->json(['message' => 'deposit successfully completed'], Response::HTTP_CREATED);

        } catch (Throwable $th) {
            return response()->json([
                'message' => sprintf('Deposit error: %s', $th->getMessage()),
            ],
                $th->getCode()
            );
        }
    }
}
