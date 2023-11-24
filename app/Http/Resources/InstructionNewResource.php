<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InstructionNewResource extends JsonResource
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
            'type' => $this->exam_type,
            'trying_number' => $this->trying_number,
            'num_of_question' => $request->type == 'online_exam' ? ($this->exam_type == 'online' ? $this->questions->count() : $this->pdf_num_questions) :  ($this->exam_type == 'all_exam' ? $this->questions->count() : $this->pdf_num_questions),
            'total_time' => $this->quize_minute,
            'instruction' => lang() == 'ar' ? $this->instruction_ar : $this->instruction_en,

        ];
    }
}
