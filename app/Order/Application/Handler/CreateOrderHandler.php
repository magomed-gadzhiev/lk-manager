<?php

namespace App\Order\Application\Handler;

use App\Application\CQRS\Contracts\CommandHandler;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Order\Application\Command\CreateOrderCommand;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

final class CreateOrderHandler implements CommandHandler
{
    public function handle(object $command): mixed
    {
        assert($command instanceof CreateOrderCommand);

        $customerData = $command->customer;
        $items = $command->items;

        $fullName = trim((string)($customerData['full_name'] ?? ''));
        $phoneRaw = (string)($customerData['phone'] ?? '');
        if ($fullName === '' || $phoneRaw === '') {
            throw new InvalidArgumentException('Поле ФИО и Телефон обязательны');
        }

        $phone = preg_replace('/\D+/', '', $phoneRaw);

        return DB::transaction(function () use ($customerData, $items, $fullName, $phone) {
            $customer = Customer::query()->where('phone', $phone)->first();
            $payload = [
                'full_name' => $fullName,
                'phone' => $phone,
                'email' => $customerData['email'] ?? null,
                'inn' => $customerData['inn'] ?? null,
                'company_name' => $customerData['company_name'] ?? null,
                'address' => $customerData['address'] ?? null,
            ];

            if ($customer) {
                $customer->fill($payload)->save();
            } else {
                $customer = Customer::query()->create($payload);
            }

            $order = Order::query()->create([
                'customer_id' => $customer->id,
                'status' => 'new',
            ]);

            foreach ($items as $item) {
                $title = trim((string)($item['title'] ?? ''));
                if ($title === '') {
                    continue;
                }
                $quantity = (float)($item['quantity'] ?? 1);
                if ($quantity <= 0) { $quantity = 1; }
                $unit = in_array(($item['unit'] ?? 'pcs'), ['pcs', 'sets'], true) ? $item['unit'] : 'pcs';

                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'title' => $title,
                    'quantity' => $quantity,
                    'unit' => $unit,
                ]);
            }

            return $order->load(['customer', 'items']);
        });
    }
}


