<?php

namespace App\Infrastructure\CQRS;

use App\Application\CQRS\Contracts\CommandBus as CommandBusContract;
use App\Application\CQRS\Contracts\CommandHandler;
use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;

final class CommandBus implements CommandBusContract
{
    /** @var array<class-string, class-string<CommandHandler>> */
    private array $map;

    public function __construct(private readonly Container $container, array $map = [])
    {
        $this->map = $map;
    }

    public function withMap(array $map): self
    {
        $clone = clone $this;
        // Merge existing map with the provided map instead of overwriting
        $clone->map = array_merge($this->map, $map);
        return $clone;
    }

    public function dispatch(object $command): mixed
    {
        $commandClass = $command::class;
        $handlerClass = $this->map[$commandClass] ?? null;

        if ($handlerClass === null) {
            throw new InvalidArgumentException("No command handler registered for {$commandClass}");
        }

        $handler = $this->container->make($handlerClass);

        if (!$handler instanceof CommandHandler) {
            throw new InvalidArgumentException($handlerClass.' must implement '.CommandHandler::class);
        }

        return $handler->handle($command);
    }
}


