<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GuideItemsResource extends JsonResource
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
            'title' => lang() == 'ar' ?$this->title_ar : $this->title_en,
            'file_path' => $this->file != null ? asset($this->file) : '',
            'file_type' => $this->file_type,
            'file_time' => $this->file_type == 'video' ? video_duration($this->file) : "" ,
            'file_path_size' => $this->file != null ? file_size($this->file) : '',
            'answer_pdf_file' => $this->answer_pdf_file != null ? asset($this->answer_pdf_file) : '',
            'answer_pdf_file_size' => $this->file != null ? file_size(asset($this->answer_pdf_file)) : '0',
            'answer_video_file' => $this->answer_video_file != null ? url(asset($this->answer_video_file)) : '',
            'answer_video_file_time' => $this->answer_video_file != null ? video_duration($this->answer_video_file) : "0" ,
            'created_at' => $this->created_at->format('Y-m-d')
        ];
    }
}
