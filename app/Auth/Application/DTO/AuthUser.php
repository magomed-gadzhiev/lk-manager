<?php

namespace App\Auth\Application\DTO;

final class AuthUser
{
    /**
     * @param string[] $roles
     */
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly array $roles,
    ) {
    }
}


