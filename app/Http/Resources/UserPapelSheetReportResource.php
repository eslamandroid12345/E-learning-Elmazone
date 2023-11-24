<?php

namespace App\Http\Resources;

use App\Models\OnlineExam;
use App\Models\PapelSheetExam;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPapelSheetReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $exam = PapelSheetExam::where('id','=', $this->papel_sheet_exam_id)->first();
        return [

            "id" =>  $this->id,
            "exam" => lang() == 'ar' ? $exam->name_ar : $exam->name_en,
            "full_degree"=> $this->degree . "/" . $exam->degree,
            "per" => (($this->degree / $exam->degree) * 100) ."%",
        ];
    }
}
