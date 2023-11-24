<?php

namespace App\Http\Controllers\Api\Guides;

use App\Http\Controllers\Controller;
use App\Http\Resources\GuideItemsResource;
use App\Http\Resources\GuideResource;
use App\Models\Guide;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuideController extends Controller
{

    public function index(){

        $guide = Guide::with('childs')
            ->whereHas('term', function ($term){
            $term->where('status', '=', 'active')
                ->where('season_id','=',auth('user-api')->user()->season_id);
        })->where('season_id','=',auth()->guard('user-api')->user()->season_id)
            ->whereNull('from_id')
            ->get();

        if($guide->count() > 0){

                return self::returnResponseDataApi(GuideResource::collection($guide),"تم وصل الدليل كامل ",200);

        }else{

            return self::returnResponseDataApi(null,"لا يوجد بيانات في الدليل الي الان ",405);
        }

    }

    public function itemsByLesson($id,$class_id): JsonResponse
    {

        $guide_items = Guide::query()
            ->where('from_id','=',$id)
            ->where('subject_class_id','=',$class_id)
            ->get();

        if($guide_items->count() > 0){

            return self::returnResponseDataApi(GuideItemsResource::collection($guide_items),"تم وصل الدليل كامل ",200);

        }else{
            return self::returnResponseDataApi(null,"لا يوجد بيانات في الدليل الي الان ",405);
        }

    }
}
