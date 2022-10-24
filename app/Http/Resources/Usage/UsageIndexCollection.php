<?php

namespace App\Http\Resources\Usage;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UsageIndexCollection extends ResourceCollection
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
            'message'   => 'Usages retrieved successfully',
            'data'      => $this->collection->transform(function ($item) {
                return [
                    'id'            => $item->id,
                    'url'           => $item->url,
                    'method'        => $item->method,
                    'action'        => $item->action,
                    'created_at'    => $item->created_at,
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
