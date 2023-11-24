<?php

namespace App\Http\Resources;

use App\Models\PapelSheetExamTime;
use Illuminate\Http\Resources\Json\JsonResource;

class PapelSheetExamTimeUserResource extends JsonResource
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
            'name' => lang() == 'ar' ?$this->name_ar : $this->name_en,
            'description' => $this->description,
            'from' => $this->from,
            'to' => $this->to,
            'date_exam' => $this->date_exam,
            'time' => PapelSheetExamTime::where('id','=',request('papel_sheet_exam_time_id'))->first(),
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->created_at->format('Y-m-d')

        ];
    }
}
