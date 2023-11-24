<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\UserSubscribe;
use App\Models\VideoOpened;
use App\Models\VideoParts;
use App\Models\VideoRate;
use App\Models\VideoTotalView;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VideoPartDetailsNewResource extends JsonResource
{

    public function toArray($request): array
    {

        $user = Auth::guard('user-api')->user();
        $videoId = $this->id;

        $user_watch_video = VideoOpened::query()
            ->where('video_part_id','=',$videoId)
            ->where('user_id','=',$user->id)
            ->first();

        $video_rate = VideoRate::query()
            ->where('video_id','=',$videoId)
            ->where('user_id','=',$user->id)
            ->first();

        $like_video_count = VideoRate::query()
            ->where('video_id','=',$videoId)
            ->where('action','=','like')
            ->count();

        $totalViews = VideoTotalView::query()
            ->where('video_part_id','=',$videoId)
            ->count();



        $sumMinutesOfVideo = VideoParts::query()
            ->where('id','=',$videoId)
            ->pluck('video_time')
            ->toArray();// example 130 seconds


        $sumAllOfMinutesVideosStudentAuth = VideoOpened::query()
            ->where('minutes','!=',null)
            ->where('video_part_id','=',$videoId)
            ->where('user_id', '=', $user->id)
            ->pluck('minutes')
            ->toArray();//example 120 seconds


        $totalMinutesOfAllVideos = number_format(((getAllSecondsFromTimes($sumAllOfMinutesVideosStudentAuth) / getAllSecondsFromTimes($sumMinutesOfVideo)) * 100),2);



        $studentAuth = User::query()
            ->where('id','=',$user->id)
            ->select('id','subscription_months_groups')
            ->first();



        $status = "not_access";

        if($studentAuth->subscription_months_groups != null){

            // Retrieve the student's subscription data
            $subscription_months_groups = json_decode($studentAuth->subscription_months_groups);

            // Determine video status based on the user's subscription and watched status
            $status = ($subscription_months_groups && in_array($this->month,$subscription_months_groups)) ?
                (!$user_watch_video ? 'lock' : ($user_watch_video->status)) : 'not_access';
        }


        return [

            'id' => $this->id,
            'name'  => lang() == 'ar' ?$this->name_ar : $this->name_en,
            'status' => $status,
            'progress' =>  !empty($sumAllOfMinutesVideosStudentAuth) ? $totalMinutesOfAllVideos : "0",
            'link' =>  $this->is_youtube == true ? $this->youtube_link :asset('videos/'. $this->link),
            'is_youtube' =>  $this->is_youtube,
            'rate' =>  $video_rate ? $video_rate->action : 'no_rate',
            'total_watch' =>   $totalViews,
            'total_like' =>   $like_video_count,
            'like_active' => $this->like_active,
            'video_minutes' => $this->video_time,
            'background_image' => $this->background_image != null ? asset('videos/images/'.$this->background_image) : asset('videos/images/default/default.png'),
            'view_active' => $this->view_active,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->created_at->format('Y-m-d'),

        ];
    }
}
