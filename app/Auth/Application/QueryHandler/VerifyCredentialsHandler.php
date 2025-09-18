<?php

namespace App\Auth\Application\QueryHandler;

use App\Application\CQRS\Contracts\QueryHandler;
use App\Auth\Application\Query\VerifyCredentialsQuery;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final class VerifyCredentialsHandler implements QueryHandler
{
    /**
     * @return User
     * @throws ValidationException
     */
    public function handle(object $query): mixed
    {
        assert($query instanceof VerifyCredentialsQuery);

        $user = User::where('email', $query->email)->first();

        if (!$user || !Hash::check($query->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Неверные учетные данные.'],
            ]);
        }

        return $user;
    }
}


