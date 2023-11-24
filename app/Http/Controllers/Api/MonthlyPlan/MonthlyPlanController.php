<?php

namespace App\Http\Controllers\Api\MonthlyPlan;

use App\Http\Controllers\Controller;
use App\Http\Resources\GetPlansByDayResource;
use App\Http\Resources\MonthlyPlanResource;
use App\Models\MonthlyPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MonthlyPlanController extends Controller{


    public function all_plans(Request $request){

        try {

            $rules = [
                'date' => 'required|date_format:Y-m-d',
            ];
            $validator = Validator::make($request->all(), $rules, [
                'date.format' => 407,
            ]);

            if ($validator->fails()) {

                $errors = collect($validator->errors())->flatten(1)[0];
                if (is_numeric($errors)) {

                    $errors_arr = [
                        407 => 'Failed,The date must be an Y-m-d',
                    ];

                    $code = collect($validator->errors())->flatten(1)[0];
                    return self::returnResponseDataApi(null, isset($errors_arr[$errors]) ? $errors_arr[$errors] : 500, $code);
                }
                return self::returnResponseDataApi(null, $validator->errors()->first(), 422);
            }

            if($request->has('date')){

                if( MonthlyPlan::where('start','=',$request->date)->exists()){

                    $plans = MonthlyPlan::where('start','=',$request->date)->whereHas('term', function ($term){
                        $term->where('status', '=', 'active')->where('season_id','=',auth('user-api')->user()->season_id);
                    })->where('season_id','=',auth()->guard('user-api')->user()->season_id)->get();

                    return self::returnResponseDataApi(MonthlyPlanResource::collection($plans), "تم ارسال تفاصيل الخطه في هذا اليوم بنجاح", 200);

                }else{

                    return self::returnResponseDataApi(null, "لا يوجد تفاصيل في هذا اليوم", 202);
                }

            }

        } catch (\Exception $exception) {

            return self::returnResponseDataApi(null, $exception->getMessage(), 500);
        }

    }

}
