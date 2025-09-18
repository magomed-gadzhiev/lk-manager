<?php

namespace App\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

final class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Биндинги домена Auth регистрировать здесь
    }

    public function boot(): void
    {
        // Загрузка политик/слушателей/роутов для домена Auth при необходимости
    }
}


