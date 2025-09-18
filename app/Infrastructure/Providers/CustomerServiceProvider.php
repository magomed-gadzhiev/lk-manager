<?php

namespace App\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

final class CustomerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Биндинги домена Customer регистрировать здесь
    }

    public function boot(): void
    {
        // Загрузка политик/слушателей/роутов для домена Customer при необходимости
    }
}


