<?php

namespace App\Infrastructure\Providers;

use App\Application\CQRS\Contracts\CommandBus as CommandBusContract;
use App\Application\CQRS\Contracts\QueryBus as QueryBusContract;
use App\Infrastructure\CQRS\CommandBus;
use App\Infrastructure\CQRS\QueryBus;
use Illuminate\Support\ServiceProvider;

final class BusServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CommandBusContract::class, function ($app) {
            return new CommandBus($app);
        });

        $this->app->singleton(QueryBusContract::class, function ($app) {
            return new QueryBus($app);
        });
    }

    public function boot(): void
    {
        // Optionally publish or configure maps here
    }
}


