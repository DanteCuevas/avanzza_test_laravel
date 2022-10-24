<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RateLimitTest extends TestCase
{

    private $credentials;
    private $token;
    private $headers;

    protected function setUp(): void
    {
        parent::setUp();
        $this->credentials = [
            'email'     => env('USER_EMAIL'),
            'password'  => env('USER_PWD'),
        ];
        $response = $this->json('POST', 'api/login', $this->credentials, ['Accept'=>'application/json']);
        $this->token = $response->getData()->token;
        $this->headers = $headers = [
            'Accept'             => 'application/json',
            'Authorization'     => 'Bearer '.$this->token
        ];
    }

    public function test_rate_limit_usages_get()
    {
        for ($i=1; $i <= 3; $i++) { 
            $response = $this->withHeaders($this->headers)->get('api/usages');
            $response->assertStatus(200);
        }

        $response = $this->withHeaders($this->headers)->get('api/usages');
        $response->assertStatus(429);
        $response->assertJson([
            "message"   => "Too Many Attempts."
        ]);

    }

}
