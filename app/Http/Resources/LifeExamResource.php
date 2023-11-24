<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LifeExamResource extends JsonResource{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request){

        return [
            'id' => $this->id,
            'name' => lang() == 'ar' ? $this->name_ar : $this->name_en,
            'date_exam' => $this->date_exam,
            'time_start' => $this->time_start,
            'time_end' => $this->time_end,
            'quiz_minute' => (int)$this->quiz_minute,
            'degree' => (int) $this->degree,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->created_at->format('Y-m-d'),
        ];
    }
}
