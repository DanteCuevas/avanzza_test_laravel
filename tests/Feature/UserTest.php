<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{

    private $credentials;
    private $token;
    
    protected function setUp():void
    {

        parent::setUp();
        $this->credentials = [
            'email'     => env('USER_EMAIL'),
            'password'  => env('USER_PWD'),
        ];
        $response = $this->json('POST', 'api/login', $this->credentials, ['Accept'=>'application/json']);
        $response = json_decode($response->getContent());
        $this->token = $response->token;

    }

    public function test_login_api_ok()
    {
        
        $response = $this->json('POST', 'api/login', $this->credentials, ['Accept'=>'application/json']);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status', 'token', 'token_type', 'user', 'message'
        ]);

    }

    public function test_login_api_fail()
    {
        
        $credentials = $this->credentials;
        $credentials['password'] = '123456789';
        $response = $this->json('POST', 'api/login', $credentials, ['Accept'=>'application/json']);
        $response->assertStatus(404);
        $response->assertJson([
            "message"   => "Invalid credentials."
        ]);

    }

    public function test_login_api_request()
    {
        
        $response = $this->json('POST', 'api/login', [], ['Accept'=>'application/json']);
        $response->assertStatus(422);
        $response->assertJson([
            "message"   => "The given data was invalid.",
            "errors"    => [
                "email"     => ["The email field is required."],
                "password"  => ["The password field is required."]
            ]
        ]);

        $credentials = [
            'email'     => 'danieldantecuevas@gmail.comss',
            'password'  => '123'
        ];
        $response = $this->json('POST', 'api/login', $credentials, ['Accept'=>'application/json']);
        $response->assertStatus(422);
        $response->assertJson([
            "message"   => "The given data was invalid.",
            "errors"    => [
                "email"     => ["The selected email is invalid."],
                "password"  => ["The password must be at least 8 characters."]
            ]
        ]);

    }

    public function test_api_access_ok()
    {

        $headers = [
            'Accept'             => 'application/json',
            'Authorization'     => 'Bearer '.$this->token
        ];
        $response = $this->withHeaders($headers)->get('api/access');
        $response->assertStatus(200);
        $response->assertJson([
            'status'    => true,
            'message'   => 'Access successfully'
        ]);

    }

    public function test_api_access_fail()
    {

        $headers = [
            'Accept'             => 'application/json',
            'Authorization'     => 'Bearer fail'
        ];
        $response = $this->json('GET', 'api/access', $headers);
        $response->assertStatus(401);
        $response->assertJson([
            'message'   => 'Unauthenticated.'
        ]);

    }

}
