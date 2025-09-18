<?php

namespace App\Application\CQRS\Contracts;

interface CommandBus
{
    /**
     * Dispatch a command to its handler and return the handler result.
     */
    public function dispatch(object $command): mixed;
}


