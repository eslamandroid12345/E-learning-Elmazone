<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VideoRateResource extends JsonResource
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

         'id' => $this->id,
         'user' => new UserResource($this->user),
         'action' => $this->action,
         'created_at' => $this->created_at->format('Y-m-d'),
         'updated_at' => $this->created_at->format('Y-m-d'),

        ];
    }
}
