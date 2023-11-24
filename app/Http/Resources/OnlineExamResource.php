<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OnlineExamResource extends JsonResource
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
             'note' => $this->note,
             'depends' => $this->depends,
             'date_exam' => $this->date_exam,
             'season_id' => $this->season_id,
             'term_id' => $this->term_id,
             'created_at' => $this->created_at->format('Y-m-d'),
             'updated_at' => $this->created_at->format('Y-m-d'),
        ];
    }
}
