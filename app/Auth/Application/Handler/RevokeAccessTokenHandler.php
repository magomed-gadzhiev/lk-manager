<?php

namespace App\Auth\Application\Handler;

use App\Application\CQRS\Contracts\CommandHandler;
use App\Auth\Application\Command\RevokeAccessTokenCommand;

final class RevokeAccessTokenHandler implements CommandHandler
{
    public function handle(object $command): mixed
    {
        assert($command instanceof RevokeAccessTokenCommand);

        $token = auth()->user()?->token();
        if ($token && $token->id === $command->tokenId) {
            $token->revoke();
        }

        return null;
    }
}


