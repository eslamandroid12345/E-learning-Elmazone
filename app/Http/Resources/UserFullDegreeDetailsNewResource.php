<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserFullDegreeDetailsNewResource extends JsonResource
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
            'name' => $this->name,
            'image' => $this->image != null ? asset('/users/'.$this->image) : asset('/default/avatar2.jfif'),
            'degree' => $this->degree,
            'per' => $this->per,
            'total_time' => (int)$this->time,
            'time_exam' => $this->time_exam,
            'city' => new CityResource($this->country->city),

        ];
    }
}
