<?php

namespace App\Auth\Application\DTO;

final class AuthResult
{
    public function __construct(
        public readonly string $token,
        public readonly AuthUser $user,
    ) {
    }
}


