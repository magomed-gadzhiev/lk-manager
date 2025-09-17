<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Роли
        $headRole = Role::firstOrCreate(['name' => 'head', 'guard_name' => 'web']);
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);

        // Пользователи по умолчанию
        $head = User::firstOrCreate(
            ['email' => 'head@example.com'],
            [
                'name' => 'Head User',
                'password' => Hash::make('password'),
            ]
        );
        $head->assignRole($headRole);

        $manager = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Manager User',
                'password' => Hash::make('password'),
            ]
        );
        $manager->assignRole($managerRole);
    }
}
