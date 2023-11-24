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
            'status' => $this->file_type == 'pdf' ? (!VideoOpened::where('video_upload_file_pdf_id','=',$this->id)
                ->where('user_id','=',Auth::guard('user-api')->id())
                ->first() ? 'lock' :  'opened') :
                (!VideoOpened::where('video_upload_file_audio_id','=',$this->id)
                ->where('user_id','=',Auth::guard('user-api')->id())
                ->first() ? 'lock' :  'opened'),
            'subscribe' => 'access',
            'size' => 1000,
            'link' =>  $this->file_type == 'pdf' ? asset('video_files/pdf/'. $this->file_link) : asset('video_files/audios/'. $this->file_link),
            'image_of_subject_class' => $this->video_part->lesson->subject_class->image == null ? asset('classes/default/def.jpg') : asset('classes/' . $this->video_part->lesson->subject_class->image),
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->created_at->format('Y-m-d'),

        ];
    }
}
