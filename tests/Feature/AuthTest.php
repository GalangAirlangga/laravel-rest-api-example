<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function testLogin(): void
    {
        $user = User::factory()->create();
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertJson([
            'success' => true,
            'data' => array(),
            'message' => 'Login Success'
        ])->assertJsonStructure([
            'success',
            'data' => [
                'token',
                'name',
                'email'
            ],
            'message'
        ]);
    }

    public function testRequiredEmailLogin(): void
    {

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => '',
            'password' => 'password'
        ]);
        $response->assertJson([
            'success' => false,
            'data' => array(
                "email" => [
                    "The email field is required."
                ]
            ),
            'message' => 'Validation error'
        ]);
    }
    public function testNotValidEmailLogin(): void
    {

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'email',
            'password' => 'password'
        ]);
        $response->assertJson([
            'success' => false,
            'data' => array(
                "email" => [
                    "The email must be a valid email address."
                ]
            ),
            'message' => 'Validation error'
        ]);
    }
    public function testNotFoundEmailLogin(): void
    {

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'email@email.com',
            'password' => 'password'
        ]);
        $response->assertJson([
            'success' => false,
            'message' => 'Account could not be found'
        ]);
    }
    public function testRequiredPasswordLogin(): void
    {

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'email@email.com',
            'password' => ''
        ]);
        $response->assertJson([
            'success' => false,
            'data' => array(
                "password" => [
                    "The password field is required."
                ]
            ),
            'message' => 'Validation error'
        ]);
    }
    public function testPasswordNotValidLogin(): void
    {
        $user = User::factory()->create();
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'notValid'
        ]);
        $response->assertJson([
            'success' => false,
            'message' => 'Unauthenticated'
        ]);
    }
}
