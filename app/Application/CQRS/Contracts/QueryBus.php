<?php

namespace App\Application\CQRS\Contracts;

interface QueryBus
{
    /**
     * Ask a query handler to handle the query and return its result.
     */
    public function ask(object $query): mixed;
}


