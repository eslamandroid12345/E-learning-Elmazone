<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentHeroResource extends JsonResource
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

            "id" => $this->id,
            "name" => $this->name,
            "country" => $this->country,
            "ordered" => $this->ordered,
            "student_total_degrees" => $this->student_total_degrees,
            "exams_total_degree" => $this->degree_online_exam + $this->degree_all_exam,
            "image" => $this->image != null ? asset($this->image) : asset('/default/avatar2.jfif')
        ];
    }
}
