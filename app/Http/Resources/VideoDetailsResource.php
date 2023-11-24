<?php

namespace App\Http\Resources;

use App\Models\VideoFavorite;
use App\Models\VideoRate;
use App\Models\VideoOpened;
use App\Models\VideoTotalView;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class VideoDetailsResource extends JsonResource{

    public function toArray($request){

        if($request->type == 'video_part'){

            $video_rate = VideoRate::query()
                ->where('video_id','=',$this->id)
                ->where('user_id','=',Auth::guard('user-api')->id())
                ->first();

            $like_video_count = VideoRate::query()
                ->where('video_id','=',$this->id)
                ->where('action','=','like')
                ->count();

            $favorite = VideoFavorite::query()
                ->where('video_part_id','=',$this->id)
                ->where('user_id','=',Auth::guard('user-api')->id())
                ->first();

            $total_views = VideoTotalView::query()
                ->where('video_part_id','=',$this->id)
                ->count();



        }elseif ($request->type == 'video_basic'){

            $video_rate = VideoRate::query()
                ->where('video_basic_id','=',$this->id)
                ->where('user_id','=',Auth::guard('user-api')->id())
                ->first();

            $like_video_count = VideoRate::query()
                ->where('video_basic_id','=',$this->id)
                ->where('action','=','like')
                ->count();

            $favorite = VideoFavorite::query()
                ->where('video_basic_id','=',$this->id)
                ->where('user_id','=',Auth::guard('user-api')->id())
                ->first();

            $total_views = VideoTotalView::query()
                ->where('video_basic_id','=',$this->id)
                ->count();

        }else{

            $video_rate = VideoRate::query()
                ->where('video_resource_id','=',$this->id)
                ->where('user_id','=',Auth::guard('user-api')->id())
                ->first();

            $like_video_count = VideoRate::query()
                ->where('video_resource_id','=',$this->id)
                ->where('action','=','like')
                ->count();


            $favorite = VideoFavorite::query()
                ->where('video_resource_id','=',$this->id)
                ->where('user_id','=',Auth::guard('user-api')->id())
                ->first();

            $total_views = VideoTotalView::query()
                ->where('video_resource_id','=',$this->id)
                ->count();
        }



        return [

            'id'                => $this->id,
            'name'          => lang() == 'ar' ?$this->name_ar : $this->name_en,
            'link'          =>   request()->type == 'video_basic' ||  request()->type == 'video_resource' ? request()->type == 'video_basic' ? ($this->is_youtube == true ? $this->youtube_link : asset('videos_basics/videos/'. $this->video_link)) : ($this->is_youtube == true ? $this->youtube_link : asset('videos_resources/videos/'. $this->video_link)) : ($this->is_youtube == true ? $this->youtube_link : asset('videos/'. $this->link)),
            'rate'          =>  $video_rate ? $video_rate->action : 'dislike',
            'is_youtube'          =>  $this->is_youtube,
            'favorite'      => $favorite ? $favorite->action : 'un_favorite',
            'total_watch'   => $total_views,
            'total_like'    => $like_video_count,
            'like_active'   => $this->like_active,
            'view_active'   => $this->view_active,
            'video_minutes' => request()->type == 'video_basic' ||  request()->type == 'video_resource' ? $this->time :$this->video_time,
            'created_at'    => $this->created_at->format('Y-m-d'),
            'updated_at'    => $this->created_at->format('Y-m-d'),

        ];


    }
}
