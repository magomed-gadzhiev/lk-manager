<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Убедимся, что есть клиенты
        if (Customer::count() === 0) {
            $this->call(CustomerSeeder::class);
        }

        $units = ['pcs', 'sets'];
        $titles = ['Товар А', 'Товар B', 'Комплект С', 'Услуга D'];

        Customer::all()->each(function (Customer $customer) use ($units, $titles) {
            // по 1-3 заказа на клиента
            $ordersToCreate = random_int(1, 3);

            for ($i = 0; $i < $ordersToCreate; $i++) {
                $order = Order::create([
                    'customer_id' => $customer->id,
                    'status' => Arr::random(['new', 'in_progress', 'done']),
                ]);

                // по 1-4 позиций в заказе
                $itemsToCreate = random_int(1, 4);
                for ($j = 0; $j < $itemsToCreate; $j++) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'title' => Arr::random($titles),
                        'quantity' => random_int(1, 5),
                        'unit' => Arr::random($units),
                    ]);
                }
            }
        });
    }
}


