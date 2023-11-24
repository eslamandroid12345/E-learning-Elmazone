<?php

namespace App\Http\Resources;

use App\Models\PhoneCommunication;
use Illuminate\Http\Resources\Json\JsonResource;

class CommunicationResource extends JsonResource
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
            'facebook_link' => $this->facebook_link,
            'youtube_link' => $this->youtube_link,
            'website_link' => $this->website_link,
            'instagram_link' => $this->instagram_link,
            'twitter_link' => $this->twitter_link,
            'whatsapp_link' => $this->whatsapp_link,
            'messenger' => $this->messenger,
            'sms' => $this->sms,
            'sms_message' => $this->sms_message,
            'phones' => PhoneCommunication::query()->get(),
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->created_at->format('Y-m-d')

        ];
    }
}
