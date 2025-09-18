<?php

namespace App\Auth\Application\Command;

final class RevokeAccessTokenCommand
{
    public function __construct(
        public readonly string $tokenId,
    ) {
    }
}


