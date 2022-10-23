<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class FilesTest extends TestCase
{

    private $fileName;
    private $credentials;
    private $token;
    private $headers;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fileName = "test_file_" . rand() . ".txt";
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
    
    public function test_file_create_success()
    {
        
        $fileName =  $this->fileName;
        $body = [
            'file'          => UploadedFile::fake()->create($fileName, 450),
            'file_name'     => $fileName
        ];
        $response = $this->json('POST', '/api/files', $body, $this->headers);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'status',
            'message',
            'data'  => [
                'id', 'file', 'file_name', 'file_url'
            ]
        ]);
        $data = $response->getData()->data;
        Storage::disk('public')->assertExists('files/' . $data->file);
    }

    public function test_file_create_request_required()
    {
        
        $response = $this->json('POST', '/api/files', [], $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            "message"   => "The given data was invalid.",
            "errors"    => [
                "file"          => ["The file field is required."],
                "file_name"     => ["The file name field is required."]
            ]
        ]);

    }

    public function test_file_create_request_rules()
    {

        $fileName =  $this->fileName;
        $body = [
            'file'          => UploadedFile::fake()->create($fileName, 501),
            'file_name'     => Str::random(101)
        ];
        $response = $this->json('POST', '/api/files', $body, $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            "message"   => "The given data was invalid.",
            "errors"    => [
                "file"          => ["The file must not be greater than 500 kilobytes."],
                "file_name"     => ["The file name must not be greater than 100 characters."]
            ]
        ]);

    }

}
