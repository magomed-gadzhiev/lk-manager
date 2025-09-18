<?php

namespace App\Http\Controllers;

use App\Application\CQRS\Contracts\CommandBus;
use App\Application\CQRS\Contracts\QueryBus;
use App\Customer\Application\Query\FindCustomerByPhoneQuery;
use App\Order\Application\Command\CreateOrderCommand;
use App\Order\Application\Query\GetOrdersQuery;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request, QueryBus $queries)
    {
        $data = $queries->ask(new GetOrdersQuery(
            search: $request->string('search')->toString() ?: null,
            dateFrom: $request->string('date_from')->toString() ?: null,
            dateTo: $request->string('date_to')->toString() ?: null,
            status: $request->string('status')->toString() ?: null,
        ));

        return response()->json(['data' => $data]);
    }

    public function store(Request $request, CommandBus $commands)
    {
        $validated = $request->validate([
            'customer.full_name' => ['required', 'string'],
            'customer.phone' => ['required', 'string'],
            'customer.email' => ['nullable', 'email'],
            'customer.inn' => ['nullable', 'string'],
            'customer.company_name' => ['nullable', 'string'],
            'customer.address' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.title' => ['required', 'string'],
            'items.*.quantity' => ['nullable', 'numeric', 'min:1'],
            'items.*.unit' => ['nullable', 'in:pcs,sets'],
        ]);

        $order = $commands->dispatch(new CreateOrderCommand(
            customer: $validated['customer'],
            items: $validated['items'],
        ));

        return response()->json(['id' => $order->id], 201);
    }

    public function findCustomer(Request $request, QueryBus $queries)
    {
        $request->validate(['phone' => ['required', 'string']]);
        $customer = $queries->ask(new FindCustomerByPhoneQuery($request->string('phone')->toString()));
        return response()->json($customer);
    }
}


