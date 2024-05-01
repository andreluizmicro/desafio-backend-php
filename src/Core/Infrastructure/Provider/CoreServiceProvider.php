<?php

declare(strict_types=1);

namespace Core\Infrastructure\Provider;

use Core\Domain\Adapter\UnitOfWorkAdapterInterface;
use Core\Domain\Gateway\AuthorizationGatewayInterface;
use Core\Domain\Notification\NotificationQueueInterface;
use Core\Domain\Repository\AccountRepositoryInterface;
use Core\Domain\Repository\TransferRepositoryInterface;
use Core\Domain\Repository\UserRepositoryInterface;
use Core\Infrastructure\Adapter\LaravelEventAdapter;
use Core\Infrastructure\Adapter\UnitOfWorkAdapter;
use Core\Infrastructure\Client\AuthorizationClient;
use Core\Infrastructure\Client\NotificationClient;
use Core\Infrastructure\Gateway\AuthorizationGateway;
use Core\Infrastructure\Repository\Mysql\AccountRepository;
use Core\Infrastructure\Repository\Mysql\TransferRepository;
use Core\Infrastructure\Repository\Mysql\UserRepository;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Shared\Domain\Adapter\EventAdapterInterface;
use Shared\Infrastructure\Queue\RabbitMQ\NotificationQueue;

class CoreServiceProvider extends ServiceProvider
{
    private const ACCEPT = 'application/json';

    private const CONTENT_TYPE = 'application/json';

    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/config.php', 'transfer_ms');
        $this->registerClients();
        $this->registerQueues();
    }

    public function register(): void
    {
        $this->app->singleton(UserRepositoryInterface::class, UserRepository::class);
        $this->app->singleton(AccountRepositoryInterface::class, AccountRepository::class);
        $this->app->singleton(TransferRepositoryInterface::class, TransferRepository::class);
        $this->app->singleton(AuthorizationGatewayInterface::class, AuthorizationGateway::class);
        $this->app->singleton(UnitOfWorkAdapterInterface::class, UnitOfWorkAdapter::class);
        $this->app->bind(EventAdapterInterface::class, LaravelEventAdapter::class);
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

    private function registerQueues(): void
    {
        $this->app->bind(NotificationQueueInterface::class, NotificationQueue::class);
    }
}
