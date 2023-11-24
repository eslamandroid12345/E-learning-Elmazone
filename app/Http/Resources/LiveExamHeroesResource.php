<?php

namespace App\Http\Resources;

use App\Models\AllExam;
use App\Models\ExamDegreeDepends;
use App\Models\LifeExam;
use App\Models\OnlineExam;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class LiveExamHeroesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request){

        $liveExam = LifeExam::query()
            ->where('id','=',$request->id)->first();

        return  [

            'id' => $this->id,
            'name' => $this->name,
            'country' => lang() == 'ar'?$this->country->name_ar : $this->country->name_en,
            'ordered' => $this->ordered,
            'image' => $this->image != null ? asset('/users/'.$this->image) : asset('/default/avatar2.jfif'),
            'student_degree' =>   ExamDegreeDepends::query()
                ->where('life_exam_id','=',$request->id)->where('user_id','=',$this->id)->first()->full_degree,
            'exam_degree' => $liveExam->degree,
            'percentage' => round((ExamDegreeDepends::query()
                            ->where('life_exam_id','=',$request->id)->where('user_id','=',$this->id)->first()->full_degree / $liveExam->degree) * 100,2) . "%"
        ];
    }
}
