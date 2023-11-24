<?php

namespace App\Http\Resources;

use App\Models\PapelSheetExamDegree;
use Illuminate\Http\Resources\Json\JsonResource;

class PapelSheetExamDegreeUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $papel_sheet_degree = PapelSheetExamDegree::where('papel_sheet_exam_id','=',$this->id)->where('user_id','=',auth('user-api')->id())->first();
        return [
            'id' => $this->id,
            'name'  => lang() == 'ar' ?$this->name_ar : $this->name_en,
            'type' => "papel_sheet",
            'status' => "approve",
            'degree' =>  $papel_sheet_degree->degree . "/" . $this->degree,
        ];
    }
}
