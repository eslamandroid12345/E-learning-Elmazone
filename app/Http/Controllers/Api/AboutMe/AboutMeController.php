<?php

namespace App\Http\Controllers\Api\AboutMe;

use App\Http\Controllers\Controller;
use App\Http\Resources\QualificationsResource;
use App\Models\Qualification;
use App\Models\Setting;
use App\Models\SocialMedia;
use Illuminate\Http\JsonResponse;

class AboutMeController extends Controller{


    public function about_me(): JsonResponse{


        $setting = Setting::query()
            ->first();

        $qualifications = Qualification::query()
            ->where('type','=','qualifications')
            ->get();

        $experiences = Qualification::query()
            ->where('type','=','experience')
            ->get();

        $skills = Qualification::query()
            ->where('type','=','skills')
            ->get();

        if($setting){

            $data['teacher_name'] = lang() == 'ar' ? $setting->teacher_name_ar : $setting->teacher_name_en;
            $data['image'] = $setting->teacher_image != null ? asset('teacher_image/'.$setting->teacher_image) : asset('teacher_image/default/avatar2.jfif');
            $data['department'] = lang() == 'ar' ? $setting->department_ar : $setting->department_en;
            $data['qualifications'] = QualificationsResource::collection($qualifications);
            $data['experiences'] = QualificationsResource::collection($experiences);
            $data['skills'] = QualificationsResource::collection($skills);
            $data['facebook_link'] =   $setting->facebook_personal;
            $data['youtube_link'] =   $setting->youtube_personal;
            $data['instagram_link'] =   $setting->instagram_personal;


            return self::returnResponseDataApi($data,"تم الحصول علي بيانات المدرس بنجاح",200);

        }else{

            return self::returnResponseDataApi(null,"لا يوجد بيانات بنظام اعدادات التطبيق",201);

        }

    }

}
