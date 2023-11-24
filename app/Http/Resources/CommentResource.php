<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'comment' => $this->comment ?? 'No comment',
            'audio' => $this->audio != null ? asset('comments_upload_file/'. $this->audio) : 'No audio',
            'image' => $this->image != null ? asset('comments_upload_file/'. $this->image) : 'No image',
            'type' => $this->type,
            'time' => $this->created_at->diffForHumans(),
            'user' => new UserResource($this->user),
            'replies' => CommentReplayResource::collection($this->replays),
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->created_at->format('Y-m-d'),
        ];
    }
}
