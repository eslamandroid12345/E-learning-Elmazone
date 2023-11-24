<?php

namespace App\Http\Resources;

use App\Models\ExamsFavorite;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class OnlineExamNewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name'  => lang() == 'ar' ?$this->name_ar : $this->name_en,
            'type' => $this->exam_type,
            'exam_type' => 'online',
            'background_color' => $this->background_color,
            'exams_favorite' => !ExamsFavorite::where('online_exam_id','=',$this->id)
                ->where('user_id','=',Auth::guard('user-api')->id())->first()
            || ExamsFavorite::where('online_exam_id','=',$this->id)->where('user_id','=',Auth::guard('user-api')->id())->where('action','=','un_favorite')->first() ? 'un_favorite' : 'favorite',
            'pdf_exam_upload' => $this->pdf_file_upload != null ? asset('online_exams/pdf_file_uploads/'. $this->pdf_file_upload) : null,
            'answer_pdf_file' => $this->answer_pdf_file != null ? asset('online_exams/pdf_answers/'. $this->answer_pdf_file) : null,
            'answer_video_file' =>  $this->answer_video_is_youtube == 1 ?  $this->answer_video_youtube : ($this->answer_video_file != null ? asset('online_exams/videos_answers/'. $this->answer_video_file) : null),
            'answer_video_is_youtube' =>  $this->answer_video_is_youtube,
            'num_of_question' => $this->exam_type == 'online' ? $this->questions->count() : $this->pdf_num_questions,
            'total_time' => $this->quize_minute,
            'exam_pdf_size' => 1000,
            'answer_pdf_size' => 900,
            'answer_video_size' => 500,
        ];
    }
}
