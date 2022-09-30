<?php

namespace App\Http\Middleware;

use App\Traits\RespondsWithHttpStatus;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Authenticate extends Middleware
{
    use RespondsWithHttpStatus;
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  Request  $request
     *
     */
    protected function redirectTo($request): Response|string|Application|ResponseFactory|null
    {
        return $this->failure('Unable to login',401);
    }
}
