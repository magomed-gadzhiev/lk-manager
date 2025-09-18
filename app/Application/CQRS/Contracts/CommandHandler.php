<?php

namespace App\Application\CQRS\Contracts;

interface CommandHandler
{
    /**
     * Handle the given command and return a result.
     */
    public function handle(object $command): mixed;
}


