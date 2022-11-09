<?php

namespace App\Services\Auth;

use App\Repository\Auth\AuthRepositoryInterface;
use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\UnauthorizedException;
use InvalidArgumentException;
use Log;
use Throwable;

class AuthService implements AuthServiceInterface
{
    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * this function for login user
     * if user have token, token will delete and generate new token
     * @param $auth
     * @return array
     * @throws Throwable
     */
    public function login($auth): array
    {
        DB::beginTransaction();
        try {
            //check if the user is registered
            if (!$this->authRepository->checkUser($auth)) {
                throw new ModelNotFoundException('Account could not be found');
            }
            //if the user is registered then login will be done
            $login = $this->authRepository->login($auth);
            //if login fails
            if (!$login) {
                throw new UnauthorizedException('Unauthenticated');
            }
            //get the data of the logged-in user
            $authUser = $this->authRepository->user();
            if (!$authUser) {
                throw new InvalidArgumentException('Unable to login');
            }
            //delete the previously created token
            if ($authUser->tokens()) {
                $authUser->tokens()->delete();
            }
            //check the role to determine ability
            $ability = match ($authUser->role) {
                'admin' => $this->authRepository->abilityAdmin(),
                'employee' => $this->authRepository->abilityEmployee()
            };
            //create token
            $token = $authUser->createToken('MyAuthApp', $ability)->plainTextToken;

            DB::commit();
            return [
                'token' => $token,
                'name' => $authUser->name,
                'email' => $authUser->email
            ];

        } catch (ModelNotFoundException|UnauthorizedException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error('login auth service : ' . $exception->getMessage());
            throw new InvalidArgumentException('Unable to login');
        }
    }

    /**
     * this function to log out and delete token
     * @param $auth
     * @return bool
     * @throws Throwable
     */
    public function logout($auth): bool
    {
        DB::beginTransaction();
        try {
            //logout user and delete token
            $this->authRepository->logout($auth);
            DB::commit();
            return true;

        } catch (ModelNotFoundException|UnauthorizedException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error('login auth service : ' . $exception->getMessage());
            throw new InvalidArgumentException('Unable to login');
        }
    }
}
