<?php

namespace Tests\Support;

use Laravel\Passport\ClientRepository;
use RuntimeException;

trait WithPassportClients
{
    protected function ensurePersonalAccessClientExists(): void
    {
        /** @var ClientRepository $repo */
        $repo = app(ClientRepository::class);

        $provider = config('auth.guards.api.provider');

        try {
            $repo->personalAccessClient($provider);
            return; // уже есть
        } catch (RuntimeException) {
            // создаём если отсутствует
            $repo->createPersonalAccessGrantClient('Personal Access Client', $provider);
        }
    }
}


