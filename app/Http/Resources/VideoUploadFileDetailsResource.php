<?php

namespace App\Http\Resources;

use App\Models\ExamsFavorite;
use App\Models\VideoRate;
use App\Models\VideoOpened;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class VideoUploadFileDetailsResource extends JsonResource
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
            'name'  => lang() == 'ar' ? $this->name_ar : $this->name_en,
            'type' => $this->file_type,
            'background_color' => $this->background_color,
            'status' => $this->checkStatus(),
            'subscribe' => 'access',
            'size' => 10,
            'link' =>  $this->file_type == 'pdf' ? asset('video_files/pdf/'. $this->file_link) : asset('video_files/audios/'. $this->file_link),
            'image_of_subject_class' => $this->video_part->lesson->subject_class->image == null ? asset('classes/default/def.jpg') : asset('classes/' . $this->video_part->lesson->subject_class->image),
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->created_at->format('Y-m-d'),

        ];
    }


    private function checkStatus(): string{

        return VideoOpened::query()
        ->where('user_id','=',userId())
        ->where('video_part_id','=',request()->id)
        ->where(function ($q){
            $q->where('status','=','opened')
                ->orWhere('status','=','watched');
        })->first() ? 'opened' : 'lock';

     }


}
