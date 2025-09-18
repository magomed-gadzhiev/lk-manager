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

        if ($query->search !== null) {
            $search = trim($query->search);
            if ($search !== '') {
                $searchLower = mb_strtolower($search, 'UTF-8');
                $builder->where(function (Builder $q) use ($search, $searchLower) {
                    $q->whereHas('customer', function (Builder $c) use ($search, $searchLower) {
                        $digits = preg_replace('/\D+/', '', $search);
                        $c->whereRaw('LOWER(full_name) LIKE ?', ['%'.$searchLower.'%'])
                            ->orWhereRaw('LOWER(company_name) LIKE ?', ['%'.$searchLower.'%']);
                        if ($digits !== '') {
                            $c->orWhere('phone', 'like', '%'.$digits.'%');
                        }
                    })
                    ->orWhereHas('items', function (Builder $i) use ($searchLower) {
                        $i->whereRaw('LOWER(title) LIKE ?', ['%'.$searchLower.'%']);
                    });
                });
            }
        }

        return $builder->get();
    }
}


