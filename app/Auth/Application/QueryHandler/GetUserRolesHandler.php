<?php

namespace App\Auth\Application\QueryHandler;

use App\Application\CQRS\Contracts\QueryHandler;
use App\Auth\Application\Query\GetUserRolesQuery;
use App\Models\User;

final class GetUserRolesHandler implements QueryHandler
{
    /**
     * @return string[]
     */
    public function handle(object $query): mixed
    {
        assert($query instanceof GetUserRolesQuery);

        /** @var User|null $user */
        $user = User::query()->find($query->userId);
        if ($user === null) {
            return [];
        }

        return $user->getRoleNames()->toArray();
    }
}


