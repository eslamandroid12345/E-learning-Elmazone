<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
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
            'answer' =>  strip_tags(str_replace('</p>', '',$this->answer)),
            'answer_number' => $this->answer_number,
            'answer_status' => $this->answer_status,

        ];
    }
}
