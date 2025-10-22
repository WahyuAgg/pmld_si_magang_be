<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Auth\AuthenticationException;

class ApiAuthenticate extends Middleware
{
    protected function unauthenticated($request, array $guards)
    {
        throw new AuthenticationException('Unauthenticated.', $guards);
    }

    protected function redirectTo($request): ?string
    {
        return null; // ⛔️ Jangan redirect ke /login
    }
}
