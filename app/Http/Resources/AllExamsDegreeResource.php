<?php

namespace App\Http\Resources;

use App\Models\AllExam;
use App\Models\ExamDegreeDepends;
use App\Models\OnlineExam;
use App\Models\OnlineExamUser;
use App\Models\PapelSheetExam;
use App\Models\PapelSheetExamDegree;
use App\Models\Timer;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Type\Time;

class AllExamsDegreeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        if($request->exam_type == 'papel_sheet'){
            $exam = PapelSheetExam::where('id','=',$request->id)->first();
            $degree = PapelSheetExamDegree::where('user_id','=',$this->id)->where('papel_sheet_exam_id','=',$request->id)->first();
            $degree = $degree->degree;



        }elseif ($request->exam_type == 'video' || $request->exam_type == 'subject_class' || $request->exam_type == 'lesson'){
            $exam = OnlineExam::where('id','=',$request->id)->first();
            $degree = ExamDegreeDepends::where('user_id','=',$this->id)
                ->where('exam_depends','=','yes')
                ->where('online_exam_id','=',$request->id)->first();
            $degree = $degree->full_degree;


        }else{
            $exam = AllExam::where('id','=',$request->id)->first();
            $degree = ExamDegreeDepends::where('user_id','=',$this->id)
                ->where('exam_depends','=','yes')
                ->where('all_exam_id','=',$request->id)->first();

            $degree = $degree->full_degree;
        }

        return  [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image != null ? asset('/users/'.$this->image) : asset('/default/avatar.jpg'),
            'percentage' => ((int)$degree / (int)$exam->degree) * 100 . "%",
        ];
    }
}
