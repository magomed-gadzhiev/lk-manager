<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Laravel\Passport\ClientRepository;
use RuntimeException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::loadKeysFrom(__DIR__.'/../secrets/oauth');

        // Автосоздание персонального клиента в dev/test окружениях
        if (app()->environment(['local', 'testing'])) {
            /** @var ClientRepository $repo */
            $repo = app(ClientRepository::class);
            $provider = config('auth.guards.api.provider');
            try {
                $repo->personalAccessClient($provider);
            } catch (RuntimeException) {
                $repo->createPersonalAccessGrantClient('Personal Access Client', $provider);
            }
        }
    }
}
