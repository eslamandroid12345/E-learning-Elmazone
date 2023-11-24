<?php

namespace App\Http\Resources;

use App\Models\OpenLesson;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class SubjectClassAllExamResource extends JsonResource
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
            'status' => OpenLesson::where('user_id','=',Auth::guard('user-api')->id())->where('subject_class_id','=',$this->id)->count() > 0 ? 'opened' : 'lock',
            'image' => $this->image == null ? asset('subject_class/default/p.png') : asset('subject_class/' . $this->image),
            'all_exams' => AllExamResource::collection($this->all_exams)

        ];
    }
}
