<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LifeExamQuestionsResource extends JsonResource
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
            'question' => $this->file_type == 'text' ? $this->question : asset('/question_images/'.$this->image),
            'question_type' => $this->question_type,
            'file_type' => $this->file_type,
            'degree' => $this->degree,
            'note' => $this->note ?? 'note',
            'remaining_time' => $this->remaining_time,
            'answers' =>  $this->question_type == 'choice' ? AnswerResource::collection($this->answers) : [],
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->created_at->format('Y-m-d'),

        ];
    }
}
