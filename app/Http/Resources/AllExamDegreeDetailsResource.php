<?php

namespace App\Http\Resources;

use App\Models\AllExam;
use App\Models\ExamDegreeDepends;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class AllExamDegreeDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $degreeDependsFullDegree = ExamDegreeDepends::query()->where('all_exam_id','=',$this->id)
            ->where('user_id','=',Auth::guard('user-api')->id())
            ->where('exam_depends','=','yes')
            ->first();

        $degreeOfAllExam = AllExam::query()->where('id','=',$this->id)->first();


        return [

            'id' => $this->id,
            'name' => lang() == 'ar' ?$this->name_ar : $this->name_en,
            'background_color' => $this->background_color,
            'percentage' => (float)number_format(($degreeDependsFullDegree->full_degree / $degreeOfAllExam->degree) * 100,2),
            'degree' =>  $degreeDependsFullDegree->full_degree."/".$degreeOfAllExam->degree,


        ];
    }
}
