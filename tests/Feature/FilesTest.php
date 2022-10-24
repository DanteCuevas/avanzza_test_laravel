<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use App\Models\File;
use Tests\TestCase;

class FilesTest extends TestCase
{
    use WithFaker;

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

    public function test_files_get_with_paginate()
    {

        $response = $this->withHeaders($this->headers)->get('api/files');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data'  => [
                '*'     => [
                    'id', 'file', 'file_name', 'file_url', 'created_at', 'file_exist',
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

    public function test_multiple_files_create_success()
    {
        
        $i = 1; $max = $this->faker->numberBetween(1, 20);
        $body = [
            'multiple_files' => []
        ];
        while ($i <= $max) {
            $fileName = "test_file_" . rand() . ".txt";
            $body['multiple_files'][] = [
                'file'          => UploadedFile::fake()->create($fileName, 200),
                'file_name'     => $fileName
            ];
            $i++;
        }
        
        $response = $this->json('POST', '/api/files/multiple-files', $body, $this->headers);
        $response->assertStatus(201);
        $data = $response->getData()->data;
        foreach ($data as $key => $file) {
            Storage::disk('public')->assertExists('files/' . $file->file);
        }
    }

    public function test_multiple_files_create_request_required()
    {

        $response = $this->json('POST', '/api/files/multiple-files', [], $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            "message"   => "The given data was invalid.",
            "errors"    => [
                "multiple_files"    => ["The multiple files field is required."],
            ]
        ]);
        $body = [
            'multiple_files' => [ [] ]
        ];
        $response = $this->json('POST', '/api/files/multiple-files', $body, $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            "message"   => "The given data was invalid.",
            "errors"    => [
                "multiple_files.0"              => ["The multiple_files.0 field is required."],
                "multiple_files.0.file"         => ["The multiple_files.0.file field is required."],
                "multiple_files.0.file_name"    => ["The multiple_files.0.file_name field is required."],
            ]
        ]);
    }

    public function test_multiple_files_create_request_rules()
    {

        $fileName =  $this->fileName;
        $body['multiple_files'][] = [
            'file'          => UploadedFile::fake()->create($fileName, 501),
            'file_name'     => Str::random(101)
        ];
        $response = $this->json('POST', '/api/files/multiple-files', $body, $this->headers);
        $response->assertStatus(422);
        $response->assertJson([
            "message"   => "The given data was invalid.",
            "errors"    => [
                "multiple_files.0.file"         => ["The multiple_files.0.file must not be greater than 500 kilobytes."],
                "multiple_files.0.file_name"    => ["The multiple_files.0.file_name must not be greater than 100 characters."],
            ]
        ]);

    }

    public function test_file_delete_success()
    {

        $file = File::orderby('id', 'asc')->where('file_exist', true)->first();
        $response = $this->withHeaders($this->headers)->delete('/api/files/'.$file->id.'/type/normal');
        $response->assertStatus(204);
        $this->assertNull(File::find($file->id));
        Storage::disk('public')->assertMissing('files/' . $file->file);

    }

    public function test_file_delete_success_logical()
    {

        $file = File::orderby('id', 'asc')->where('file_exist', true)->first();
        $response = $this->withHeaders($this->headers)->delete('/api/files/'.$file->id.'/type/logical');
        $response->assertStatus(204);
        $this->assertNull(File::find($file->id));
        Storage::disk('public')->assertExists('files/deleted' . $file->file);

    }

    public function test_file_delete_success_physical()
    {

        $file = File::orderby('id', 'asc')->where('file_exist', true)->first();
        $response = $this->withHeaders($this->headers)->delete('/api/files/'.$file->id.'/type/physical');
        $response->assertStatus(204);
        $this->assertNotNull(File::find($file->id));
        Storage::disk('public')->assertMissing('files/' . $file->file);

    }

}
