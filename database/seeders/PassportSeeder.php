<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Laravel\Passport\ClientRepository;
use RuntimeException;

class PassportSeeder extends Seeder
{
    public function run(): void
    {
        /** @var ClientRepository $repo */
        $repo = app(ClientRepository::class);

        $provider = config('auth.guards.api.provider');

        try {
            // Если персональный клиент уже существует — ничего не делаем
            $repo->personalAccessClient($provider);
        } catch (RuntimeException) {
            // Создаём персональный клиент для провайдера users
            $repo->createPersonalAccessGrantClient('Personal Access Client', $provider);
        }
    }
}


