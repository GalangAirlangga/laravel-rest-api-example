<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthServiceInterface;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use InvalidArgumentException;
use Log;
use Throwable;


class AuthController extends Controller
{
    use RespondsWithHttpStatus;

    private AuthServiceInterface $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request): Response|Application|ResponseFactory
    {
        $validatedData = $request->safe()->only([
            'email',
            'password'
        ]);
        try {
            $auth = $this->authService->login($validatedData);
            return $this->success('Login Success', $auth);
        } catch (InvalidArgumentException $exception) {
            return $this->failure($exception->getMessage(), 404);
        } catch (Throwable $exception) {
            Log::error('Login : ' . $exception->getMessage());
            return $this->failure('Unable to login');
        }

    }

    public function logout(Request $request): Response|Application|ResponseFactory
    {
        try {
            $this->authService->logout($request);
            return $this->success('Logout Success');
        } catch (InvalidArgumentException $exception) {
            return $this->failure($exception->getMessage(), 404);
        } catch (Throwable $exception) {
            Log::error('Login : ' . $exception->getMessage());
            return $this->failure('Unable to login');
        }

    }
}
