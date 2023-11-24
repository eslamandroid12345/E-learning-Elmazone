<?php

namespace App\Http\Resources;

use App\Models\AllExam;
use App\Models\ExamDegreeDepends;
use App\Models\Timer;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class AllExamInstructionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request){
        $depends = ExamDegreeDepends::where('all_exam_id', '=',$this->all_exam_id)->where('user_id', '=', Auth::guard('user-api')->id())
            ->where('exam_depends', '=', 'yes')->first();
        $trying = Timer::where('all_exam_id',$this->all_exam_id)->where('user_id','=',auth('user-api')->id())->count();
        return [
            'id' => $this->id,
            'instruction' => $this->instruction,
            'trying_number' => !$depends?((int)$this->all_exam->trying_number - (int)$trying):0,
            'number_of_question' => $this->number_of_question,
            'quiz_minute' => $this->all_exam->quize_minute,
            'all_exam_id' => $this->all_exam_id,
            'exam_type' => "full_exam",
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->created_at->format('Y-m-d')
        ];
    }
}
