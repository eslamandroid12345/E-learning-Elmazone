<?php

namespace App\Http\Resources;

use App\Models\Lesson;
use App\Models\SubjectClass;
use Illuminate\Http\Resources\Json\JsonResource;

class TextYourselfExamResource extends JsonResource
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
            'questions_type' => $this->questions_type,
            'total_time' => (int)$this->total_time,
            'exam_degree' => $this->total_degree,
            'num_of_questions' => (int)$this->num_of_questions,
            'questions' => QuestionResource::collection($this->questions),
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->created_at->format('Y-m-d')

        ];
    }
}
