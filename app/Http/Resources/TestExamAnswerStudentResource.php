<?php

namespace App\Http\Resources;

use App\Models\OnlineExamUser;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class TestExamAnswerStudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $answerStudent = OnlineExamUser::query()
        ->where('user_id','=',Auth::guard('user-api')->id())
            ->where('question_id','=',$this->id)
            ->where('test_yourself_exam_id','=',$request->id)
            ->whereHas('question', function ($q){
                $q->where('question_type','=','choice');
            })
            ->first();

        return [

            'id' => $this->id,
            'question' => $this->file_type == 'text' ? $this->question : asset('/question_images/'.$this->image),
            'answer_user' => $answerStudent->answer_id,
            'answer_user_type' => 'id',
            'question_type' => $this->question_type,
            'file_type' => $this->file_type,
            'degree' => $this->degree,
            'note' => $this->note ?? 'note',
            'answers' =>  QuestionAnswersNewResource::collection($this->answers),
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->created_at->format('Y-m-d'),
        ];
    }
}
