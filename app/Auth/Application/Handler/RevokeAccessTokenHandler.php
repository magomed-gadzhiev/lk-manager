<?php

namespace App\Auth\Application\Handler;

use App\Application\CQRS\Contracts\CommandHandler;
use App\Auth\Application\Command\RevokeAccessTokenCommand;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Token;

final class RevokeAccessTokenHandler implements CommandHandler
{
    public function handle(object $command): mixed
    {
        assert($command instanceof RevokeAccessTokenCommand);

        /** @var Token|null $token */
        $token = Token::query()->find($command->tokenId);

        $currentUserId = Auth::guard('api')->id() ?? Auth::id();

        if ($token && $currentUserId !== null && (int) $currentUserId === (int) $token->user_id) {
            $token->revoke();
        }

        return null;
    }
}


