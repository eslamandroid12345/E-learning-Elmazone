<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OnBoardingResource;
use App\Models\OnBoarding;
use Illuminate\Http\Request;

class OnBoardingController extends Controller
{
    public function index(){
       $slides = OnBoarding::get();

       return self::returnResponseDataApi(OnBoardingResource::collection($slides)," تم وصول جميع البيانات بنجاح",200);
    }
}
