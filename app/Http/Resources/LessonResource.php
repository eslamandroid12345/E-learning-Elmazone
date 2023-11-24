<?php

namespace App\Http\Resources;

use App\Models\OpenLesson;
use App\Models\Timer;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class LessonResource extends JsonResource
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
             'name' => lang() == 'ar' ?$this->name_ar : $this->name_en,
             'note' => $this->note,
             'status' => OpenLesson::where('user_id','=',Auth::guard('user-api')->id())->where('lesson_id','=',$this->id)->count() > 0 ? 'opened' : 'lock',
             'videos_count' => 4,
             'videos_time' => 120,
             'created_at' => $this->created_at->format('Y-m-d'),
             'updated_at' => $this->created_at->format('Y-m-d'),

        ];
    }
}
