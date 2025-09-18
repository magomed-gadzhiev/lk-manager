<?php

namespace App\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use App\Application\CQRS\Contracts\CommandBus as CommandBusContract;
use App\Application\CQRS\Contracts\QueryBus as QueryBusContract;
use App\Auth\Application\Command\IssueAccessTokenCommand;
use App\Auth\Application\Command\RevokeAccessTokenCommand;
use App\Auth\Application\Handler\IssueAccessTokenHandler;
use App\Auth\Application\Handler\RevokeAccessTokenHandler;
use App\Auth\Application\Query\GetUserRolesQuery;
use App\Auth\Application\Query\VerifyCredentialsQuery;
use App\Auth\Application\QueryHandler\GetUserRolesHandler;
use App\Auth\Application\QueryHandler\VerifyCredentialsHandler;

final class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Биндинги домена Auth: регистрируем соответствия команд и запросов их хэндлерам
        $this->app->extend(CommandBusContract::class, function ($bus, $app) {
            return $bus->withMap(array_merge([
                IssueAccessTokenCommand::class => IssueAccessTokenHandler::class,
                RevokeAccessTokenCommand::class => RevokeAccessTokenHandler::class,
            ], []));
        });

        $this->app->extend(QueryBusContract::class, function ($bus, $app) {
            return $bus->withMap(array_merge([
                VerifyCredentialsQuery::class => VerifyCredentialsHandler::class,
                GetUserRolesQuery::class => GetUserRolesHandler::class,
            ], []));
        });
    }

    public function boot(): void
    {
        // Загрузка политик/слушателей/роутов для домена Auth при необходимости
    }
}


