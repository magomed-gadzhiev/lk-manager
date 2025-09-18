<?php

namespace App\Customer\Application\QueryHandler;

use App\Application\CQRS\Contracts\QueryHandler;
use App\Customer\Application\Query\FindCustomerByPhoneQuery;
use App\Models\Customer;

final class FindCustomerByPhoneHandler implements QueryHandler
{
    public function handle(object $query): mixed
    {
        assert($query instanceof FindCustomerByPhoneQuery);

        $normalized = preg_replace('/\D+/', '', $query->phone);

        return Customer::query()->where('phone', $normalized)->first();
    }
}


