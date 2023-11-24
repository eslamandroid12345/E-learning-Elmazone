<?php
namespace App\Http\Resources;
use App\Models\ExamsFavorite;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class VideoPartOnlineExamsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    //grt all data of videos
    public function toArray($request){
        return [

            'id' => $this->id,
            'name'  => lang() == 'ar' ? $this->name_ar : $this->name_en,
            'exams_favorite' => !ExamsFavorite::where('online_exam_id','=',$this->id)->where('user_id','=',Auth::guard('user-api')->id())->first()
            || ExamsFavorite::where('online_exam_id','=',$this->id)->where('user_id','=',Auth::guard('user-api')->id())->where('action','=','un_favorite')->first() ? 'un_favorite' : 'favorite',
            'background_color' => $this->background_color,
            'num_of_question' =>  $this->questions->count(),
            'total_time' => $this->quize_minute,

        ];
    }
}
