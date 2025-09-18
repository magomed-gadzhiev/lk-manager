<?php

namespace App\Infrastructure\CQRS;

use App\Application\CQRS\Contracts\QueryBus as QueryBusContract;
use App\Application\CQRS\Contracts\QueryHandler;
use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;

final class QueryBus implements QueryBusContract
{
    /** @var array<class-string, class-string<QueryHandler>> */
    private array $map;

    public function __construct(private readonly Container $container, array $map = [])
    {
        $this->map = $map;
    }

    public function withMap(array $map): self
    {
        $clone = clone $this;
        $clone->map = $map;
        return $clone;
    }

    public function ask(object $query): mixed
    {
        $queryClass = $query::class;
        $handlerClass = $this->map[$queryClass] ?? null;

        if ($handlerClass === null) {
            throw new InvalidArgumentException("No query handler registered for {$queryClass}");
        }

        $handler = $this->container->make($handlerClass);

        if (!$handler instanceof QueryHandler) {
            throw new InvalidArgumentException($handlerClass.' must implement '.QueryHandler::class);
        }

        return $handler->handle($query);
    }
}


