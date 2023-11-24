<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SuggestResource extends JsonResource
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
            'suggestion' => $this->suggestion ?? 'No suggestion',
            'audio' => $this->audio != null ? asset('suggestions_uploads/audios/'. $this->audio) : 'No audio',
            'image' => $this->image != null ? asset('suggestions_uploads/images/'. $this->image) : 'No image',
            'type' => $this->type,
            'user' => new UserResource($this->user),
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->created_at->format('Y-m-d')

        ];
    }
}
