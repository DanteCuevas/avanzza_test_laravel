<?php

namespace App\Http\Resources\File;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MultipleFileCollection extends ResourceCollection
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
            'message'   => 'Files created successfully',
            'data'      => $this->collection->transform(function ($item) {
                return [
                    'file'          => $item['file'],
                    'file_name'     => $item['file_name'],
                ];
            })
            
        ];
    }
}
