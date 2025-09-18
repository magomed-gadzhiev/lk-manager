<?php

namespace App\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use App\Application\CQRS\Contracts\QueryBus as QueryBusContract;
use App\Customer\Application\Query\FindCustomerByPhoneQuery;
use App\Customer\Application\QueryHandler\FindCustomerByPhoneHandler;

final class CustomerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Биндинги домена Customer
//        $this->app->extend(QueryBusContract::class, function ($bus, $app) {
//            return $bus->withMap(array_merge([
//                FindCustomerByPhoneQuery::class => FindCustomerByPhoneHandler::class,
//            ], []));
//        });
    }

    public function boot(): void
    {
        // Загрузка политик/слушателей/роутов для домена Customer при необходимости
    }
}


