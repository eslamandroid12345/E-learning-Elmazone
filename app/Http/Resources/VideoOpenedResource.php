<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VideoOpenedResource extends JsonResource
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
            'minutes' => $this->minutes,
            'status' => $this->status,
            'type' => $this->type,
            'lesson_id' => $this->video->lesson->id,
            'user' => $this->user->name,

        ];
    }

    //end
}
