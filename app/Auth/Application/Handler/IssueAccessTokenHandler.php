<?php

namespace App\Auth\Application\Handler;

use App\Application\CQRS\Contracts\CommandHandler;
use App\Auth\Application\Command\IssueAccessTokenCommand;
use App\Models\User;

final class IssueAccessTokenHandler implements CommandHandler
{
    public function handle(object $command): mixed
    {
        assert($command instanceof IssueAccessTokenCommand);

        /** @var User $user */
        $user = User::query()->findOrFail($command->userId);

        return $user->createToken($command->tokenName)->accessToken;
    }
}


