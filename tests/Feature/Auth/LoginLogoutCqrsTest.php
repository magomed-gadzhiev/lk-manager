<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;

class LoginLogoutCqrsTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_and_logout_flow(): void
    {
        $role = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $user = User::factory()->create([
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole($role);

        $resp = $this->postJson('/api/login', [
            'email' => 'manager@example.com',
            'password' => 'password',
        ]);

        $resp->assertOk()
            ->assertJsonStructure([
                'token',
                'user' => ['id', 'name', 'email', 'roles'],
            ]);

        $token = $resp->json('token');
        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/logout')
            ->assertOk();
    }
}
