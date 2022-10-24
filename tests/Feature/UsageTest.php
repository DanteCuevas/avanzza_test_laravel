<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use App\Models\File;
use Tests\TestCase;

class UsageTest extends TestCase
{

    private $credentials;
    private $token;
    private $headers;

    protected function setUp(): void
    {
        parent::setUp();
        $this->credentials = [
            'email'     => 'danieldantecuevas@gmail.com',
            'password'  => '12345678'
        ];
        $response = $this->json('POST', 'api/login', $this->credentials, ['Accept'=>'application/json']);
        $this->token = $response->getData()->token;
        $this->headers = $headers = [
            'Accept'             => 'application/json',
            'Authorization'     => 'Bearer '.$this->token
        ];
    }

    public function test_usages_get_with_paginate()
    {

        $response = $this->withHeaders($this->headers)->get('api/usages');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data'  => [
                '*'     => [
                    'id', 'url', 'method', 'action', 'created_at',
                    'user'      => [
                        'id', 'name', 'email',
                    ]
                ]
            ],
            'meta'  => [
                'current_page', 'from', 'last_page', 'per_page', 'to', 'total'
            ]
        ]);

    }

}
