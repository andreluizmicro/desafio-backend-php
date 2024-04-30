<?php

declare(strict_types=1);

namespace App\Providers;

use App\Adapter\UnitOfWorkAdapter;
use App\Client\AuthorizationClient;
use App\Client\NotificationClient;
use App\Gateway\AuthorizationGateway;
use App\Gateway\NotificationGateway;
use App\Repositories\Mysql\AccountRepository;
use App\Repositories\Mysql\TransferRepository;
use App\Repositories\Mysql\UserRepository;
use Core\Domain\Adapter\UnitOfWorkAdapterInterface;
use Core\Domain\Gateway\AuthorizationGatewayInterface;
use Core\Domain\Gateway\NotificationGatewayInterface;
use Core\Domain\Repository\AccountRepositoryInterface;
use Core\Domain\Repository\TransferRepositoryInterface;
use Core\Domain\Repository\UserRepositoryInterface;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    private const ACCEPT = 'application/json';

    private const CONTENT_TYPE = 'application/json';

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerClients();
    }

    public function register(): void
    {
        $this->app->singleton(UserRepositoryInterface::class, UserRepository::class);
        $this->app->singleton(AccountRepositoryInterface::class, AccountRepository::class);
        $this->app->singleton(TransferRepositoryInterface::class, TransferRepository::class);
        $this->app->singleton(AuthorizationGatewayInterface::class, AuthorizationGateway::class);
        $this->app->singleton(NotificationGatewayInterface::class, NotificationGateway::class);
        $this->app->singleton(UnitOfWorkAdapterInterface::class, UnitOfWorkAdapter::class);
    }

    private function registerClients(): void
    {
        $this->app->singleton(AuthorizationClient::class, function () {
            $client = new Client([
                'base_uri' => env('AUTHORIZATION_CLIENT_BASE_URL'),
                'timeout' => env('AUTHORIZATION_CLIENT_TIMEOUT', 10),
                'headers' => [
                    'Accept' => self::ACCEPT,
                    'Content-Type' => self::CONTENT_TYPE,
                ],
            ]);

            return new AuthorizationClient($client);
        });

        $this->app->singleton(NotificationClient::class, function () {
            $client = new Client([
                'base_uri' => env('NOTIFICATION_CLIENT_BASE_URL'),
                'timeout' => env('NOTIFICATION_CLIENT_TIMEOUT', 10),
                'headers' => [
                    'Accept' => self::ACCEPT,
                    'Content-Type' => self::CONTENT_TYPE,
                ],
            ]);

            return new NotificationClient($client);
        });
    }
}
