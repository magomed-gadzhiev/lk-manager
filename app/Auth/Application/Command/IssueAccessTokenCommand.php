<?php

namespace App\Auth\Application\Command;

final class IssueAccessTokenCommand
{
    public function __construct(
        public readonly int $userId,
        public readonly string $tokenName = 'api',
    ) {
    }
}


