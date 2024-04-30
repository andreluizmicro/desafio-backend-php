<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transfer\CreateTransferFormRequest;
use Core\Application\Transfer\Create\CreateTransferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class TransferController extends Controller
{
    public function __construct(
        private CreateTransferService $createTransferService,
    ) {
    }

    public function create(CreateTransferFormRequest $request): JsonResponse
    {
        try {
            $transferId = $this->createTransferService->execute($request->toDto());

            return response()->json($transferId, Response::HTTP_CREATED);
        } catch (Throwable $th) {
            return response()->json(['message' => 'Transaction error'], Response::HTTP_BAD_REQUEST);
        }
    }
}
