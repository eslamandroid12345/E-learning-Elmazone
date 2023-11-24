<?php

namespace App\Http\Resources;

use App\Models\ExamsFavorite;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class LiveExamFavoriteResource extends JsonResource
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
            'exams_favorite' => !ExamsFavorite::where('life_exam_id','=',$this->id)
                ->where('user_id','=',Auth::guard('user-api')->id())->first()
            || ExamsFavorite::where('life_exam_id','=',$this->id)
                ->where('user_id','=',Auth::guard('user-api')->id())
                ->where('action','=','un_favorite')->first() ? 'un_favorite' : 'favorite',
            'answer_video_file' => $this->answer_video_file != null ? asset($this->answer_video_file) : null,

        ];
    }
}
