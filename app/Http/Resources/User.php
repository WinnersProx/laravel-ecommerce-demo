<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $extra = ['is_healthy' => true];

        $base =  [
            'name' => $this->name,
            'roles' => $this->when(
                $request->user()->hasRole('admin'),
                $request->user->roles
            )
        ];

        return $base + $extra;
    }
}
