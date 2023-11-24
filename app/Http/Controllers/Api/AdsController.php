<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdsResource;
use App\Http\Resources\LifeExamResource;
use App\Models\Ads;
use App\Models\ExamDegreeDepends;
use App\Models\LifeExam;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdsController extends Controller
{
    public function index(){
        $ads = Ads::where('status',1)->get();
        //start life exam show
        $life_exam = LifeExam::whereHas('term', function ($term){
            $term->where('status','=','active')->where('season_id','=',auth('user-api')->user()->season_id);
        })->where('season_id','=',auth()->guard('user-api')->user()->season_id)
                ->where('time_start','>',Carbon::now()->format('h:i'))
            ->first();


        if($ads->count() > 0){

            return self::returnResponseDataApi(['ads'=>AdsResource::collection($ads),'life_exam'=>new LifeExamResource($life_exam)],"جميع الاعلانات ",200);

        }else{

            return self::returnResponseDataApi(['life_exam'=>new LifeExamResource($life_exam)],"لا يوجد بيانات في العلانات الي الان ",200);
        }

    }
}
