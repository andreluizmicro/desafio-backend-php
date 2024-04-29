<?php

namespace App\Exceptions;

use Core\Domain\Exception\AlreadyExistsException;
use Core\Domain\Exception\NotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $exception) {
        });
    }

    public function render($request, Throwable $exception): JsonResponse
    {
        if ($exception instanceof NotFoundException)
            return $this->showError($exception->getMessage(), Response::HTTP_NOT_FOUND);

        if ($exception instanceof AlreadyExistsException)
            return $this->showError($exception->getMessage(), Response::HTTP_CONFLICT);

        return parent::render($request, $exception);
    }

    private function showError(string $message, int $statusCode): JsonResponse
    {
        return response()->json([
            'message' => $message
        ], $statusCode);
    }
}
