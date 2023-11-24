<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MonthlyPlanResource;
use App\Http\Resources\SubscribeResource;
use App\Models\MonthlyPlan;
use App\Models\Subscribe;
use App\Models\UserSubscribe;

class SubscribeController extends Controller
{
    public function all(){

        try {
            $user = auth()->guard('user-api')->user();
//            $months = getFromToMonthsList($user->date_start_code, $user->date_end_code);
//            dd($months);
            $start_year = date('Y') -1;
            $end_year = date('Y');
            if(date('j') > 8){
                $start_year = date('Y');
                $end_year = date('Y') + 1;
            }
            $months = UserSubscribe::query()
            ->where('student_id',$user->id)
                ->where(function($query) use($start_year,$end_year){
                    return  $query->where('year',$start_year)->orWhere('year',$end_year);
                })->pluck('month')
                ->toArray();



            $subscribes = Subscribe::where('season_id','=',auth()->guard('user-api')->user()->season_id)
                    ->where(
                        function($query) use($start_year,$end_year){
                          return  $query->where('year',$start_year)->orWhere('year',$end_year);
                        }
                )->whereNotIn('month',$months)->get();

            $months = UserSubscribe::where('student_id',$user->id)->where(
                function($query) use($start_year,$end_year){
                    return  $query->where('year',$start_year)->orWhere('year',$end_year);
                }
            )->pluck('month')->toArray();
//            $dates = getFromToFromMonthsList($months);

            $subscribes->map(function ($subscribe)  {
//                dd($subscribe->year."-".month_with_zero($subscribe->month)."-31");
                $subscribe->plan = MonthlyPlanResource::collection(MonthlyPlan::where('start' ,'>=', $subscribe->year."-".month_with_zero($subscribe->month)."-01")->where('end','<=',$subscribe->year."-".month_with_zero($subscribe->month)."-31")->get());
//                return [
//                    'id'            => $product->id,
//                    'title'         => $product->title,
//                    'available'     => $unavailableProducts['result']->contains('id', $product->id),
//                ];
                return $subscribe;
            });


            return self::returnResponseDataApi(SubscribeResource::collection($subscribes), "تم الحصول علي بيانات الاشتراكات بنجاح", 200);
        } catch (\Exception $exception) {

            return self::returnResponseDataApi(null, $exception->getMessage(), 500);
        }

    }

}
