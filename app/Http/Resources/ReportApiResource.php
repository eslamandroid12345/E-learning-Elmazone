<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        if($this->type == 'video_part'){

            $title = lang() == 'ar' ? $this->video_part->name_ar : $this->video_part->name_en;

        }elseif ($this->type == 'video_resource_id'){

            $title = lang() == 'ar' ? $this->video_resource->name_ar : $this->video_resource->name_en;

        }else{

            $title = lang() == 'ar' ? $this->video_basic->name_ar : $this->video_basic->name_en;

        }

        return [

           'id' => $this->id,
           'title' => $title ,
           'type' => $this->type,
           'report' => $this->report,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->created_at->format('Y-m-d'),


        ];
    }
}
