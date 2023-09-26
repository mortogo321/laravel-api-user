<?php

namespace App\Extensions;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Session;

class ApiUserProvider implements UserProvider
{
    public function retrieveById($identifier): Authenticatable | null
    {
        return Session::get('api-user') ?? null;
    }

    public function retrieveByToken($identifier, $token): Authenticatable | null
    {
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token): void
    {
    }

    public function retrieveByCredentials(array $credentials): Authenticatable | null
    {
        return new User($credentials) ?? null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        $user = new User($credentials) ?? null;

        if (!$user) {
            return false;
        }

        return $user->apiUserValidate();
    }
}
