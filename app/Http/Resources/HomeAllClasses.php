<?php

namespace App\Http\Resources;

use App\Models\OpenLesson;
use App\Models\VideoOpened;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class HomeAllClasses extends JsonResource
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
            'status' => OpenLesson::where('user_id','=',Auth::guard('user-api')->id())
                ->where('subject_class_id','=',$this->id)->first() ? 'opened' : 'lock',
            'title' => lang() == 'ar' ? $this->title_ar : $this->title_en,
            'num_of_lessons' => $this->lessons->count(),
        ];
    }
}
