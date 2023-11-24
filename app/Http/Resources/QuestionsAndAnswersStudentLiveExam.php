<?php

namespace App\Http\Resources;

use App\Models\OnlineExamUser;
use App\Models\TextExamUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class QuestionsAndAnswersStudentLiveExam extends JsonResource
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
            ->whereHas('question', fn (Builder $builder) =>
                $builder->where('question_type','=','choice'))
            ->first();


        return [

            'id' => $this->id,
            'question_type' => $this->question_type,
            'question' => strip_tags(str_replace('</p>', '', $this->question)),
            'image' => asset($this->image),
            'answer_user_type' => 'id',
            'answer_user' => $answerStudent->answer_id,
            'question_degree' => $this->degree,
            'answers' =>  QuestionAnswersNewResource::collection($this->answers),

        ];
    }
}
