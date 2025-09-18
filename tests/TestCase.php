<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Support\WithPassportClients;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, WithPassportClients;

    protected function setUp(): void
    {
        parent::setUp();

        // Убедиться, что в тестовой БД есть personal access client
        $this->ensurePersonalAccessClientExists();
    }
}
