<?php

namespace App\Http\Resources;

use App\Models\AllExam;
use App\Models\ExamDegreeDepends;
use App\Models\OnlineExam;
use App\Models\Timer;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ExamInstructionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if($this->online_exam->type == 'lesson'){
            $type  = 'lesson';
        }elseif ($this->online_exam->type == 'subject_class'){
            $type  = 'subject_class';
        }else{
            $type  = 'video';
        }

        $depends = ExamDegreeDepends::where('online_exam_id', '=',$this->online_exam_id)->where('user_id', '=', Auth::guard('user-api')->id())
            ->where('exam_depends', '=', 'yes')->first();
        $trying = Timer::where('online_exam_id',$this->online_exam_id)->where('user_id','=',auth('user-api')->id())->count();
        return [

            'id' => $this->id,
            'instruction' => $this->instruction,
            'trying_number' =>  !$depends?((int)$this->online_exam->trying_number - (int)$trying) : 0,
            'number_of_question' => $this->number_of_question,
            'quiz_minute' => $this->online_exam->quize_minute,
            'online_exam_id' => $this->online_exam_id,
            'exam_type' =>  $type,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->created_at->format('Y-m-d')

        ];
    }
}
