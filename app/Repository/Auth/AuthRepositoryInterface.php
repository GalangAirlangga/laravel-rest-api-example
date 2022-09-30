<?php

namespace App\Repository\Auth;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

interface AuthRepositoryInterface
{
    public function login($auth): bool;

    public function logout($auth);

    public function user(): User|Authenticatable|null;

    public function checkUser($auth): User|null;

    public function abilityAdmin(): array;

    public function abilityEmployee(): array;

}
