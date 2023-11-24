<?php

namespace App\Http\Resources;

use App\Models\VideoOpened;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoOpenedWithStudentNewResource extends JsonResource
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
            'link' =>  asset('videos/'. $this->link),
            'time' => $this->video_time,
            'size' => 1000,
             'videoOpened' => new VideoOpenedResource(VideoOpened::query()
                 ->where('video_part_id','=',$this->id)
                 ->where('user_id','=',auth('user-api')->id())->first()),
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->created_at->format('Y-m-d'),

        ];
    }
}//update class
