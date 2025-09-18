<?php

namespace App\Order\Application\Command;

/**
 * Команда создания заказа.
 * $customer: [full_name, phone, email?, inn?, company_name?, address?]
 * $items: array<array{title:string, quantity:float|int, unit:'pcs'|'sets'}>
 */
final class CreateOrderCommand
{
    /** @param array<string,mixed> $customer */
    /** @param array<int,array<string,mixed>> $items */
    public function __construct(
        public readonly array $customer,
        public readonly array $items,
    ) {
    }
}


