<?php

namespace App\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use App\Application\CQRS\Contracts\CommandBus as CommandBusContract;
use App\Application\CQRS\Contracts\QueryBus as QueryBusContract;
use App\Order\Application\Command\CreateOrderCommand;
use App\Order\Application\Handler\CreateOrderHandler;
use App\Order\Application\Query\GetOrdersQuery;
use App\Order\Application\QueryHandler\GetOrdersHandler;

final class OrderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Биндинги домена Order
        $this->app->extend(CommandBusContract::class, function ($bus, $app) {
            return $bus->withMap(array_merge([
                CreateOrderCommand::class => CreateOrderHandler::class,
            ], []));
        });

        $this->app->extend(QueryBusContract::class, function ($bus, $app) {
            return $bus->withMap(array_merge([
                GetOrdersQuery::class => GetOrdersHandler::class,
            ], []));
        });
    }

    public function boot(): void
    {
        // Загрузка политик/слушателей/роутов для домена Order при необходимости
    }
}


