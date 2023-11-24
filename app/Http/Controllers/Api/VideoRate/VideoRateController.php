<?php

namespace App\Http\Controllers\Api\VideoRate;

use App\Http\Controllers\Controller;
use App\Http\Resources\VideoRateResource;
use App\Models\VideoBasic;
use App\Models\VideoParts;
use App\Models\VideoRate;
use App\Models\VideoResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VideoRateController extends Controller{


    public function user_rate_video($id,Request $request): JsonResponse
    {

        $rules = [
            'type' => 'required|in:video_id,video_basic_id,video_resource_id',
            'action' => 'required|in:like,dislike,no_rate',
        ];
        $validator = Validator::make($request->all(), $rules, [
            'type.in' => 406,
            'action.in' => 407
        ]);

        if ($validator->fails()) {

            $errors = collect($validator->errors())->flatten(1)[0];
            if (is_numeric($errors)) {

                $errors_arr = [
                    406 => 'Failed,The type must be an video_id or video_basic_id or video_resource_id',
                    407 => 'Failed,The action type must be like or dislike or no_rate',
                ];

                $code = collect($validator->errors())->flatten(1)[0];
                return self::returnResponseDataApi( null,isset($errors_arr[$errors]) ? $errors_arr[$errors] : 500, $code);
            }
            return self::returnResponseDataApi(null,$validator->errors()->first(),422);
        }

        if(request('type') == 'video_id'){

            $video = VideoParts::query()->where('id','=',$id)->first();
            if(!$video){
                return self::returnResponseDataApi(null,"هذا الفيديو غير موجود",404,404);
            }


            $video_rate = VideoRate::updateOrCreate(
                [
                    'user_id'   => Auth::guard('user-api')->id(),
                    'video_id' => $video->id,
                ],
                [
                    'video_id' => $video->id,
                    'action' => $request->action
                ]
            );

            if($video_rate->save()){
                return self::returnResponseDataApi(new VideoRateResource($video_rate),"تم تقييم فيديو الشرح بنجاح",200);

            }else{
                return self::returnResponseDataApi(null,"يوجد خطاء ما اثناء دخول البيانات برجاء الرجوع للباك اند",500);

            }

        }elseif (request('type') == 'video_basic_id'){

            $video = VideoBasic::query()->where('id','=',$id)->first();
            if(!$video){
                return self::returnResponseDataApi(null,"فيديو الاساسيات غير موجود",404,404);
            }


            $video_rate = VideoRate::updateOrCreate(
                [
                    'user_id'   => Auth::guard('user-api')->id(),
                    'video_basic_id' => $video->id,
                ],
                [
                    'video_basic_id' => $video->id,
                    'action' => $request->action
                ]
            );

            if($video_rate->save()){
                return self::returnResponseDataApi(new VideoRateResource($video_rate),"تم تقييم فيديو اساسيات الشرح بنجاح",200);

            }else{
                return self::returnResponseDataApi(null,"يوجد خطاء ما اثناء دخول البيانات برجاء الرجوع للباك اند",500);

            }

        }else{

            $video = VideoResource::query()->where('id','=',$id)->first();
            if(!$video){
                return self::returnResponseDataApi(null,"فيديو المراجعه غير موجود",404,404);
            }


            $video_rate = VideoRate::updateOrCreate(
                [
                    'user_id'   => Auth::guard('user-api')->id(),
                    'video_resource_id' => $video->id,
                ],
                [
                    'video_resource_id' => $video->id,
                    'action' => $request->action
                ]
            );

            if($video_rate->save()){
                return self::returnResponseDataApi(new VideoRateResource($video_rate),"تم تقييم فيديو المراجعه بنجاح",200);

            }else{
                return self::returnResponseDataApi(null,"يوجد خطاء ما اثناء دخول البيانات برجاء الرجوع للباك اند",500);

            }
        }


    }

}
