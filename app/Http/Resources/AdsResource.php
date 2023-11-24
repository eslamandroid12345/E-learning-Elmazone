<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdsResource extends JsonResource
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
            'link' => $this->link,
            'type' => $this->type,
            'file_path' => $this->file != null ? asset('assets/uploads/ads/'.$this->file) : '',
            'created_at' => $this->created_at->format('Y-m-d')
        ];
    }
}
