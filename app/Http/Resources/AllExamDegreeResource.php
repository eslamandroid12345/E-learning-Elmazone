<?php

namespace App\Http\Resources;

use App\Models\Degree;
use App\Models\ExamDegreeDepends;
use Illuminate\Http\Resources\Json\JsonResource;

class AllExamDegreeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $degree = Degree::where('user_id','=',auth()->id())->where('all_exam_id','=',$this->id)->groupBy('all_exam_id')->sum('degree');
        $exam_degree_depends = ExamDegreeDepends::where('user_id','=',auth()->id())->where('all_exam_id','=',$this->id)
            ->get();

        $status = "";
        foreach ($exam_degree_depends as $exam_degree_depend){
            if($exam_degree_depend->exam_depends == 'yes'){
                $status = "depends";
            }else{
                $status = "not_depends";
            }
        }

        $degrees_users = Degree::where('user_id','=',auth()->id())->where('all_exam_id','=',$this->id)->get();
        foreach ($degrees_users as $degrees_user){
            if($degrees_user->status == 'not_completed'){
                $status = "not_completed";
            }
        }
        return [

            'id' => $this->id,
            'name' => lang() == 'ar' ?$this->name_ar : $this->name_en,
            'type' => 'all_exam',
            'status' => $status,
            'degree' =>  (int)$degree . "/" . (int)$this->degree,
        ];
    }
}
