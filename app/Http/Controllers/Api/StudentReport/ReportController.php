<?php

namespace App\Http\Controllers\Api\StudentReport;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserExamReportResource;
use App\Http\Resources\UserPapelSheetReportResource;
use App\Http\Resources\UserReportResource;
use App\Http\Resources\UserResource;
use App\Models\ExamDegreeDepends;
use App\Models\PapelSheetExamDegree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller{



    public function student_report(){

        $user = new UserReportResource(Auth::guard('user-api')->user());
        $exams = ExamDegreeDepends::where('user_id','=',Auth::guard('user-api')->id())->where('exam_depends','=','yes')->get();
        $all_exams = UserExamReportResource::collection($exams);
        $papel_sheet_user_degree = UserPapelSheetReportResource::collection(PapelSheetExamDegree::where('user_id','=',Auth::guard('user-api')->id())->get());

        return response()->json(['data' => ['user' => $user,'exams' => $all_exams,'papel_sheet' => $papel_sheet_user_degree] , 'message' => "تم الحصول علي تقرير درجات الطالب بجميع الامتحانات" , 'code' => 200],200);
    }

}
