<?php

namespace App\Http\Resources;

use App\Models\Term;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
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
            'report' => route('autoPrintReport',$this->id),
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'season' => new SeasonResource($this->season),
            'term' => new TermResource(Term::where('status','=','active')->where('season_id','=',Auth::guard('user-api')->user()->season_id)->first()),
            'father_phone' => $this->father_phone,
            'image' => $this->image != null ? asset($this->image) : asset('default/avatar2.jfif'),
            'user_status' => $this->user_status,
             'center' => lang() == 'ar' ? ($this->center == 'in' ? 'سنتر' : 'خارج السنتر') : ($this->center == 'in' ? 'center' : 'out center'),
            'code' => $this->code,
            'date_start_code' => $this->date_start_code,
            'date_end_code' => $this->date_end_code,
            'city' => new CityResource($this->country->city),
            'country' => new CountryResource($this->country),
            'token' => 'Bearer ' . $this->token,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->created_at->format('Y-m-d'),
        ];
    }
}
