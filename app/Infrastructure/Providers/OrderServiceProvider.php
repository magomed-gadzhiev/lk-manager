<?php

namespace App\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

final class OrderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Биндинги домена Order регистрировать здесь
    }

    public function boot(): void
    {
        // Загрузка политик/слушателей/роутов для домена Order при необходимости
    }
}


