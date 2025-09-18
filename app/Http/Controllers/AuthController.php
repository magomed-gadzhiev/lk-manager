<?php

namespace App\Http\Controllers;

use App\Application\CQRS\Contracts\CommandBus;
use App\Application\CQRS\Contracts\QueryBus;
use App\Auth\Application\Command\IssueAccessTokenCommand;
use App\Auth\Application\Command\RevokeAccessTokenCommand;
use App\Auth\Application\DTO\AuthResult;
use App\Auth\Application\DTO\AuthUser;
use App\Auth\Application\Query\GetUserRolesQuery;
use App\Auth\Application\Query\VerifyCredentialsQuery;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request, QueryBus $queries, CommandBus $commands)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = $queries->ask(new VerifyCredentialsQuery($credentials['email'], $credentials['password']));
        $roles = $queries->ask(new GetUserRolesQuery($user->id));
        $token = $commands->dispatch(new IssueAccessTokenCommand($user->id, 'api'));

        $dto = new AuthResult(
            token: $token,
            user: new AuthUser(
                id: $user->id,
                name: $user->name,
                email: $user->email,
                roles: $roles,
            )
        );

        return response()->json([
            'token' => $dto->token,
            'user' => [
                'id' => $dto->user->id,
                'name' => $dto->user->name,
                'email' => $dto->user->email,
                'roles' => $dto->user->roles,
            ],
        ]);
    }

    public function logout(Request $request, CommandBus $commands)
    {
        $token = $request->user()->token();
        if ($token) {
            $commands->dispatch(new RevokeAccessTokenCommand($token->id));
        }

        return response()->json(['message' => 'Logged out']);
    }
}
