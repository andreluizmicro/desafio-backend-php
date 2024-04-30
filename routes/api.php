<?php

declare(strict_types=1);

use Core\Infrastructure\Http\Controller\AccountController;
use Core\Infrastructure\Http\Controller\TransferController;
use Core\Infrastructure\Http\Controller\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/users')->group(function () {
    Route::post('/', [UserController::class, 'create'])
        ->name('users.create');
});

Route::prefix('v1/accounts')->group(function () {
    Route::post('/', [AccountController::class, 'create'])
        ->name('accounts.create');

    Route::post('/deposit/{account_id}', [AccountController::class, 'deposit'])
        ->name('accounts.deposit');
});

Route::prefix('v1/transfers')->group(function () {
    Route::post('/', [TransferController::class, 'create'])
        ->name('transfers.create');
});
