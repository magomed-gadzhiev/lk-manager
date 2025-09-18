<?php

namespace Tests\Unit\Auth;

use App\Auth\Application\Command\IssueAccessTokenCommand;
use App\Auth\Application\Command\RevokeAccessTokenCommand;
use App\Auth\Application\Handler\IssueAccessTokenHandler;
use App\Auth\Application\Handler\RevokeAccessTokenHandler;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Tests\TestCase;

class RevokeAccessTokenHandlerTest extends TestCase
{
    use RefreshDatabase;

    public function test_revokes_current_user_token(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'password' => Hash::make('secret'),
        ]);

        /** @var AuthenticatableContract $authUser */
        $authUser = $user;
        $this->actingAs($authUser, 'api');

        $issue = new IssueAccessTokenHandler();
        $tokenString = $issue->handle(new IssueAccessTokenCommand($user->id, 'api'));

        // Найти токен пользователя
        /** @var User $userModel */
        $userModel = User::query()->findOrFail($user->id);
        $token = $userModel->tokens()->latest()->first();

        $handler = new RevokeAccessTokenHandler(request());
        $handler->handle(new RevokeAccessTokenCommand((string) $token->id));

        $token->refresh();
        $this->assertTrue($token->revoked);
    }
}


