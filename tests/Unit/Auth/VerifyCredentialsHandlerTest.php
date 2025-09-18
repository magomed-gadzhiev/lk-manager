<?php

namespace Tests\Unit\Auth;

use App\Auth\Application\Query\VerifyCredentialsQuery;
use App\Auth\Application\QueryHandler\VerifyCredentialsHandler;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class VerifyCredentialsHandlerTest extends TestCase
{
    use RefreshDatabase;

    public function test_handles_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('secret'),
        ]);

        $handler = new VerifyCredentialsHandler();
        $result = $handler->handle(new VerifyCredentialsQuery('test@example.com', 'secret'));

        $this->assertSame($user->id, $result->id);
    }

    public function test_throws_on_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('secret'),
        ]);

        $handler = new VerifyCredentialsHandler();

        $this->expectException(ValidationException::class);
        $handler->handle(new VerifyCredentialsQuery('test@example.com', 'wrong'));
    }
}


