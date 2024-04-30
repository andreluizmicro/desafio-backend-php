<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\CreateAccountFormRequest;
use App\Http\Requests\Account\DepositAccountFormRequest;
use Core\Application\Account\Create\CreateAccountService;
use Core\Application\Account\Deposit\DepositAccountService;
use Core\Domain\Exception\UserException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class AccountController extends Controller
{
    public function __construct(
        private CreateAccountService $createAccountService,
        private DepositAccountService $depositAccountService,
    ) {
    }

    public function create(CreateAccountFormRequest $request): JsonResponse
    {
        try {
            $accountId = $this->createAccountService->execute($request->toDto());

            return response()->json($accountId, Response::HTTP_CREATED);
        } catch (UserException $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (Throwable $th) {
            return response()->json(['message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function deposit(DepositAccountFormRequest $request)
    {
        try {
            $output = $this->depositAccountService->execute($request->toDto());

            return response()->json($output, Response::HTTP_CREATED);

        } catch (Throwable $th) {
            return response()->json(['success' => false], Response::HTTP_BAD_REQUEST);
        }
    }
}
