<?php

namespace Tests\Unit\Auth;

use App\Auth\Application\Command\IssueAccessTokenCommand;
use App\Auth\Application\Command\RevokeAccessTokenCommand;
use App\Auth\Application\Handler\IssueAccessTokenHandler;
use App\Auth\Application\Handler\RevokeAccessTokenHandler;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\TokenRepository;
use Tests\TestCase;

class RevokeAccessTokenHandlerTest extends TestCase
{
    use RefreshDatabase;

    public function test_revokes_current_user_token(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('secret'),
        ]);

        $this->be($user, 'api');

        $issue = new IssueAccessTokenHandler();
        $tokenString = $issue->handle(new IssueAccessTokenCommand($user->id, 'api'));

        // Найти токен пользователя
        $repo = app(TokenRepository::class);
        $token = $user->tokens()->latest()->first();

        $handler = new RevokeAccessTokenHandler();
        $handler->handle(new RevokeAccessTokenCommand($token->id));

        $token->refresh();
        $this->assertTrue($token->revoked);
    }
}


