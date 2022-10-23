<?php

namespace App\Http\Resources\File;

use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'status'    => true,
            'message'   => 'Create file successfully.',
            'data'      => [
                'id'            => $this->id,
                'file'          => $this->file,
                'file_name'     => $this->file_name,
                'file_url'      => $this->file_url
            ]
        ];
    }
}
