<?php

namespace Tests\Unit\Auth;

use App\Auth\Application\Command\IssueAccessTokenCommand;
use App\Auth\Application\Handler\IssueAccessTokenHandler;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class IssueAccessTokenHandlerTest extends TestCase
{
    use RefreshDatabase;

    public function test_issues_token_for_user(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('secret'),
        ]);

        $handler = new IssueAccessTokenHandler();
        $token = $handler->handle(new IssueAccessTokenCommand($user->id, 'api'));

        $this->assertIsString($token);
        $this->assertNotEmpty($token);
    }
}


