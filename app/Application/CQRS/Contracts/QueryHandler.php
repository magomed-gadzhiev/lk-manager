<?php

namespace App\Application\CQRS\Contracts;

interface QueryHandler
{
    /**
     * Handle the given query and return a result.
     */
    public function handle(object $query): mixed;
}


