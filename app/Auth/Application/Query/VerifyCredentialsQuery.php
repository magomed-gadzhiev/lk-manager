<?php

namespace App\Auth\Application\Query;

final class VerifyCredentialsQuery
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {
    }
}


