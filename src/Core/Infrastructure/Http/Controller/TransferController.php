<?php

declare(strict_types=1);

namespace Core\Infrastructure\Http\Controller;

use App\Http\Controllers\Controller;
use Core\Application\Transfer\Create\CreateTransferService;
use Core\Infrastructure\Http\Request\Transfer\CreateTransferFormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class TransferController extends Controller
{
    public function __construct(
        private readonly CreateTransferService $createTransferService,
    ) {
    }

    public function create(CreateTransferFormRequest $request): JsonResponse
    {
        try {
            $transferIdCreated = $this->createTransferService->execute($request->toDto());

            return response()->json(['transfer_id' => $transferIdCreated->transferId], Response::HTTP_CREATED);
        } catch (Throwable $th) {
            return response()->json(['message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
