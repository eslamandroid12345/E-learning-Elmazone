<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassesWithLessonsResource extends JsonResource
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
            'name'  => lang() == 'ar' ?$this->name_ar : $this->name_en,
            'image' => $this->image == null ? asset('classes/default/p.png') : asset('classes/' . $this->image),
            'limit_of_questions' => $this->questions()->count(),
            'lessons' => LessonsOfSubjectClassDetailsResource::collection($this->lessons),

        ];
    }
}
