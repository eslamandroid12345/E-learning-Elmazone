<?php

namespace App\Http\Resources;

use App\Models\NotificationSeenStudent;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class NotificationResource extends JsonResource{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

    $notification_seen = NotificationSeenStudent::query()
        ->where('student_id','=',Auth::guard('user-api')->id())
        ->where('notification_id','=',$this->id)
        ->first();

        return [

            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'notification_type' => $this->notification_type,
            'video_type' => $this->video_type,
            'video_id' => $this->video_id,
            'exam_type' => $this->exam_type,
            'exam_id' => $this->exam_id,
            'seen' => $notification_seen ? 'seen' : 'not_seen',
            'image' => $this->image != null ? asset($this->image) : null,
            'time_of_notification' => $this->created_at->diffForHumans(),
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->created_at->format('Y-m-d'),
        ];
    }
}
