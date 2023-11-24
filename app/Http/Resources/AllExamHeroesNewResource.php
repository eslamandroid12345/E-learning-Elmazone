<?php

namespace App\Http\Resources;

use App\Models\ExamDegreeDepends;
use App\Models\LifeExam;
use Illuminate\Http\Resources\Json\JsonResource;

class AllExamHeroesNewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request){



        return  [

            'id' => $this->id,
            'name' => $this->name,
            'country' => lang() == 'ar'?$this->country->name_ar : $this->country->name_en,
            'ordered' => $this->ordered,
            'student_total_degrees' => (int)$this->degree,
            'exams_total_degree' => (int)$this->exams_total_degree,
            'image' => $this->image != null ? asset('/users/'.$this->image) : asset('/default/avatar2.jfif'),

        ];
    }

}
