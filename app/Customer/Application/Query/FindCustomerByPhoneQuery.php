<?php

namespace App\Customer\Application\Query;

final class FindCustomerByPhoneQuery
{
    public function __construct(public readonly string $phone)
    {
    }
}


