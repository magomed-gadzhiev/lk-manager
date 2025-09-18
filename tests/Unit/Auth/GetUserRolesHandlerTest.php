<?php

namespace Tests\Unit\Auth;

use App\Auth\Application\Query\GetUserRolesQuery;
use App\Auth\Application\QueryHandler\GetUserRolesHandler;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GetUserRolesHandlerTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_roles_of_existing_user(): void
    {
        $user = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $user->assignRole($role);

        $handler = new GetUserRolesHandler();
        $roles = $handler->handle(new GetUserRolesQuery($user->id));

        $this->assertContains('manager', $roles);
    }

    public function test_returns_empty_array_for_missing_user(): void
    {
        $handler = new GetUserRolesHandler();
        $roles = $handler->handle(new GetUserRolesQuery(999999));

        $this->assertSame([], $roles);
    }
}
