<?php

namespace App\Http\Resources\File;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FileCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'success'   => true,
            'message'   => 'Files retrieved successfully',
            'data'      => $this->collection->transform(function ($item) {
                return [
                    'id'            => $item->id,
                    'file'          => $item->file,
                    'file_name'     => $item->file_name,
                    'file_url'      => $item->file_url,
                    'created_at'    => $item->created_at,
                    'file_exist'    => $item->file_exist,
                    'user'          => [
                        'id'            => $item->user_id,
                        'name'          => $item->user->name,
                        'email'         => $item->user->email
                    ]
                ];
            })
            
        ];
    }
}
