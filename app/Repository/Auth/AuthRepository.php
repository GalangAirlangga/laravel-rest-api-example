<?php

namespace App\Repository\Auth;

use App\Models\User;
use Auth;
use Illuminate\Contracts\Auth\Authenticatable;

class AuthRepository implements AuthRepositoryInterface
{

    /**
     * This function for login user
     * @param $auth
     * @return bool
     */
    public function login($auth): bool
    {
        return Auth::attempt($auth);
    }

    /**
     * This function for logout user
     * @param $auth
     * @return bool
     */
    public function logout($auth):bool
    {
        return $auth->user()->currentAccessToken()->delete();
    }

    /**
     * This function to get user data after login
     * @return User|Authenticatable|null
     */
    public function user(): User|Authenticatable|null
    {
       return Auth::user();
    }

    /**
     * This function is to check registered users based on the email entered when logging in
     * @param $auth
     * @return User|null
     */
    public function checkUser($auth): User|null
    {
       return User::whereEmail($auth['email'])->first();
    }

    /**
     * this function for get data ability admin
     * @return string[]
     */
    public function abilityAdmin(): array
    {
        return array(
            'department-create',
            'department-edit',
            'department-index',
            'department-show',
            'department-delete',
            'position-create',
            'position-edit',
            'position-index',
            'position-show',
            'position-delete',
            'employee-create',
            'employee-edit',
            'employee-index',
            'employee-show',
            'employee-delete'
        );
    }

    /**
     * this function for get data ability employee
     * @return string[]
     */
    public function abilityEmployee(): array
    {
        return array(
            'department-index',
            'department-show',
            'position-index',
            'position-show',
            'employee-index',
            'employee-show'
        );
    }
}
