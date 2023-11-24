<?php

namespace App\Http\Resources;

use App\Models\AllExam;
use App\Models\LifeExam;
use App\Models\OnlineExam;
use Illuminate\Http\Resources\Json\JsonResource;

class UserExamReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        if($this->online_exam_id != null){

            $exam = OnlineExam::where('id','=', $this->online_exam_id)->first();
        }elseif ($this->all_exam_id != null){
            $exam = AllExam::where('id','=', $this->all_exam_id)->first();
        }else{

            $exam = LifeExam::where('id','=', $this->life_exam_id)->first();
        }

        return [

            "id" =>  $this->id,
            "exam" => lang() == 'ar' ? $exam->name_ar : $exam->name_en,
            "full_degree"=> $this->full_degree . "/" . $exam->degree,
            "per" => (($this->full_degree / $exam->degree) * 100) ."%",
        ];

        /*
         *   "online_exam_id"=> $this->online_exam_id,
            "all_exam_id"=> $this->all_exam_id,
            "life_exam_id"=> $this->life_exam_id,
         */
    }
}
