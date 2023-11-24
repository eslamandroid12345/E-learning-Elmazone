<?php

namespace App\Http\Resources;

use App\Models\UserSubscribe;
use App\Models\VideoParts;
use App\Models\VideoRate;
use App\Models\VideoOpened;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class VideoPartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request){

        if($this->type == "video"){
            $link = asset('videos/'. $this->link);
        }elseif ($this->type == "audio"){

            $link = asset('audios/'. $this->link);
        }else{

            $link = asset('pdf/'. $this->link);
        }

        $user_watch_video = VideoOpened::where('video_part_id','=',$this->id)->where('user_id','=',Auth::guard('user-api')->id())->first();

        $watched = "lock";
        if($user_watch_video){
        if($user_watch_video->status == 'opened' || 'watched'){
            $watched = 'opened';
        }
        }

        $like_video_count = VideoRate::where('video_id','=',$this->id)->where('action','=','like')->count();
        $dislike_video_count = VideoRate::where('video_id','=',$this->id)->where('action','=','dislike')->count();

        $video_rate = VideoRate::where('video_id','=',$this->id)->where('user_id','=',Auth::guard('user-api')->id())->first();
        if($video_rate){
            $rate = $video_rate->action;
        }else{
            $rate = "no_rate";
        }


        $user_subscribes = UserSubscribe::query()
        ->where('student_id','=',Auth::guard('user-api')->id())
            ->where('year','=',Carbon::now()->format('Y'))
            ->pluck('month')->toArray();

        $user_access_video = VideoParts::where('id','=',$this->id)
            ->where('month','=',Carbon::now()->format('m'))
            ->whereIn('month',$user_subscribes)->first();


        return [
            'id' => $this->id,
            'name' => lang() == 'ar' ?$this->name_ar : $this->name_en,
            'note' => $this->note ?? 'No notes',
            'link' => $link,
            'type' => $this->type,
            'ordered' => $this->ordered,
            'status' => $watched,
            'subscribe' =>  count($user_subscribes) > 0 ? ($user_access_video ? 'access' : 'not_access') : 'not_access',
            'rate' => $rate,
             'like_count' => $like_video_count,
             'dislike_count' => $dislike_video_count,
            'video_time' => (int)$this->video_time,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->created_at->format('Y-m-d'),
            'exams' => OnlineExamResource::collection($this->exams),
        ];
    }
}
