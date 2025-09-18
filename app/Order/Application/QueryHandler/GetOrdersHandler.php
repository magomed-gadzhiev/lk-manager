<?php

namespace App\Order\Application\QueryHandler;

use App\Application\CQRS\Contracts\QueryHandler;
use App\Models\Order;
use App\Order\Application\Query\GetOrdersQuery;
use Illuminate\Database\Eloquent\Builder;

final class GetOrdersHandler implements QueryHandler
{
    public function handle(object $query): mixed
    {
        assert($query instanceof GetOrdersQuery);

        $builder = Order::query()
            ->with(['customer', 'items'])
            ->orderByDesc('created_at');

        if ($query->status) {
            $builder->where('status', $query->status);
        }

        if ($query->dateFrom) {
            $builder->whereDate('created_at', '>=', $query->dateFrom);
        }

        if ($query->dateTo) {
            $builder->whereDate('created_at', '<=', $query->dateTo);
        }

        if ($query->search) {
            $search = trim($query->search);
            $builder->where(function (Builder $q) use ($search) {
                $q->whereHas('customer', function (Builder $c) use ($search) {
                    $c->where('full_name', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', '%'.preg_replace('/\D+/', '', $search).'%');
                })
                ->orWhereHas('items', function (Builder $i) use ($search) {
                    $i->where('title', 'like', "%{$search}%");
                });
            });
        }

        return $builder->get();
    }
}


