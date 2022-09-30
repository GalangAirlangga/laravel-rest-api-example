<?php

namespace App\Services\Auth;

interface AuthServiceInterface
{
    public function login($auth):array;
    public function logout($auth):bool;
}
