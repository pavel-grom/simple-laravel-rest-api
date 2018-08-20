<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    private $authJsonStructure = [
        'data' => ['access_token', 'token_type', 'expires_in'],
        'result'
    ];

    private $userJsonStructure = [
        'data' => ['id', 'name', 'email', 'created_at', 'updated_at'],
        'result'
    ];

    /**
     * register and login test
     *
     * @return void
     */
    public function testRegisterAndLoginAndRefreshAndGetUser()
    {
        $this->json('POST', '/api/register', [
                'email' => 'some@email.com',
                'name' => 'some name',
                'password' => 'secret',
                'password_confirmation' => 'secret',
            ])
            ->assertStatus(200)
            ->assertJsonStructure($this->authJsonStructure);

        $response = $this->json('POST', '/api/login', [
                'email' => 'some@email.com',
                'password' => 'secret',
            ])
            ->assertStatus(200)
            ->assertJsonStructure($this->authJsonStructure)
            ->getContent();

        $response = json_decode($response, true);

        $token = $response['data']['access_token'];

        $this->withHeaders([
                'Authorization' => $token,
            ])
            ->json('GET', '/api/refresh')
            ->assertStatus(200)
            ->assertJsonStructure($this->authJsonStructure);

        $this->withHeaders([
                'Authorization' => $token,
            ])
            ->json('GET', '/api/user')
            ->assertStatus(200)
            ->assertJsonStructure($this->userJsonStructure);
    }
}
