<?php

namespace App\Http\Resources;

use App\Models\Term;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserReportResource extends JsonResource
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
            'phone' => $this->phone,
            'season' => new SeasonResource($this->season),
            'term' => new TermResource(Term::where('status','=','active')->where('season_id','=',Auth::guard('user-api')->user()->season_id)->first()),
            'country' => new CountryResource($this->country),
            'father_phone' => $this->father_phone,
            'image' => $this->image != null ? asset('/users/'.$this->image) : asset('/default/avatar.jpg'),
        ];
    }
}
