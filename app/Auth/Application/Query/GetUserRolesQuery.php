<?php

namespace App\Auth\Application\Query;

final class GetUserRolesQuery
{
    public function __construct(
        public readonly int $userId,
    ) {
    }
}


