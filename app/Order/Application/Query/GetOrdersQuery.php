<?php

namespace App\Order\Application\Query;

final class GetOrdersQuery
{
    public function __construct(
        public readonly ?string $search = null,
        public readonly ?string $dateFrom = null, // YYYY-MM-DD
        public readonly ?string $dateTo = null,   // YYYY-MM-DD
        public readonly ?string $status = null,   // new|in_progress|done
    ) {
    }
}


