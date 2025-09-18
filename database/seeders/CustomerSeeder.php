<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'full_name' => 'Иванов Иван Иванович',
                'phone' => '79990000001',
                'email' => 'ivanov@example.com',
                'inn' => '7700000001',
                'company_name' => 'ООО Ромашка',
                'address' => 'г. Москва, ул. Пушкина, д. 1',
            ],
            [
                'full_name' => 'Петров Пётр Петрович',
                'phone' => '79990000002',
                'email' => 'petrov@example.com',
                'inn' => '7700000002',
                'company_name' => 'ООО Василёк',
                'address' => 'г. Санкт-Петербург, Невский пр., д. 10',
            ],
            [
                'full_name' => 'Сидорова Анна Сергеевна',
                'phone' => '79990000003',
                'email' => 'sidorova@example.com',
                'inn' => '7700000003',
                'company_name' => 'ИП Сидорова',
                'address' => 'г. Казань, ул. Баумана, д. 5',
            ],
        ];

        foreach ($customers as $data) {
            Customer::firstOrCreate(
                ['phone' => $data['phone']],
                $data
            );
        }
    }
}


