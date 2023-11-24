<?php

namespace App\Http\Resources;

use App\Models\OnlineExamUser;
use App\Models\TextExamUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class QuestionsNewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        //في حاله الاجابه لسؤال اختياري
        $onlineExamUser = OnlineExamUser::query()
        ->where('user_id','=',Auth::guard('user-api')->id())
            ->where('question_id','=',$this->id)
            ->whereHas('question', fn (Builder $builder) =>
                $builder->where('question_type','=','choice'))
            ->first();

        //في حاله الاجابه لسؤال مقالي
        $textExamUser = TextExamUser::query()
            ->where('user_id','=',Auth::guard('user-api')->id())
            ->where('question_id','=',$this->id)
            ->whereHas('question', fn (Builder $builder) =>
            $builder->where('question_type','=','text'))
            ->first();


        //تفقد في حاله نوع السؤال مقالي
        if($this->question_type == 'text'){

            if($textExamUser->answer_type == 'file'){

                $answerStudentForThisQuestion = asset('text_user_exam_files/images/'.$this->image);

            }elseif ($textExamUser->answer_type == 'audio'){

                $answerStudentForThisQuestion = asset('text_user_exam_files/audios/'.$this->audio);
            }else{

                $answerStudentForThisQuestion = $textExamUser->answer;
            }

        }else{

            $answerStudentForThisQuestion = $onlineExamUser->answer_id;
        }

        return [

             'id' => $this->id,
            'question_type' => $this->question_type,
            'question' => strip_tags(str_replace('</p>', '', $this->question)),
            'image' => asset($this->image),
            'answer_user_type' => ($this->question_type == 'choice' ? 'id' : $textExamUser->answer_type),
            'answer_user' => $answerStudentForThisQuestion,
            'question_degree' => $this->degree,
            'answers' =>  $this->question_type == 'choice' ? QuestionAnswersNewResource::collection($this->answers) : [],

        ];
    }
}
