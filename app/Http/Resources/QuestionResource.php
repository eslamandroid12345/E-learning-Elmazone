<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
            'question' => $this->file_type == 'text' ?  strip_tags(str_replace('</p>', '',$this->question)) : asset('/question_images/'.$this->image),
            'image' => $this->image != null ? asset($this->image) : null,
            'question_type' => $this->question_type,
            'file_type' => $this->file_type,
            'degree' => $this->degree,
            'answers' =>  $this->question_type == 'choice' ? AnswerResource::collection($this->answers) : [],


        ];
    }
}//last update question resource
