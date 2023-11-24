<?php

namespace App\Http\Controllers\Api\Degree;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllExamDegreeResource;
use App\Http\Resources\AllExamResource;
use App\Http\Resources\OnlineExamDegreeResource;
use App\Http\Resources\OnlineExamResource;
use App\Http\Resources\PapelSheetExamDegreeUserResource;
use App\Http\Resources\PapelSheetResource;
use App\Models\AllExam;
use App\Models\Degree;
use App\Models\ExamDegreeDepends;
use App\Models\OnlineExam;
use App\Models\OnlineExamQuestion;
use App\Models\PapelSheetExam;
use App\Models\SubjectClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DegreeController extends Controller{


    public function degrees(){


        $examVideos =  OnlineExam::with(['term'])->whereHas('term', function ($term){
            $term->where('status', '=', 'active')->where('season_id','=',auth('user-api')->user()->season_id);
        })->whereHas('exam_degree_depends')->where('season_id','=',auth()->guard('user-api')->user()->season_id)
            ->where('type','=','video')->get();


        $lessons_or_subject_classes = OnlineExam::with(['term'])->whereHas('term', function ($term){
            $term->where('status', '=', 'active')->where('season_id','=',auth('user-api')->user()->season_id);
        })->where('season_id','=',auth()->guard('user-api')->user()->season_id)
            ->whereNotIn('type',['video','all_exam'])
            ->whereHas('exam_degree_depends')
            ->get();


       $all_exams = AllExam::whereHas('term', function ($term){
           $term->where('status', '=', 'active')->where('season_id','=',auth('user-api')->user()->season_id);
       })->whereHas('exam_degree_depends')->where('season_id','=',auth()->guard('user-api')->user()->season_id)->get();


        $papelSheetExam = PapelSheetExam::where('season_id','=',auth()->guard('user-api')->id())->whereHas('term', function ($term){
            $term->where('status', '=', 'active')->where('season_id','=',auth('user-api')->user()->season_id);
        })->get();

        return response()->json([

           "data" => [
               "videos" => OnlineExamDegreeResource::collection($examVideos),
               "all_exams" => AllExamDegreeResource::collection($all_exams),
               "subject_classes" => OnlineExamDegreeResource::collection($lessons_or_subject_classes),
               "papel_sheet" => PapelSheetExamDegreeUserResource::collection($papelSheetExam),
           ],
           "message" => "تم الحصول علي جميع درجات الامتحانات التابعه لهذا الطالب بنجاح",
           "code" => 200

       ],200);



    }


    public function degrees_depends(Request $request,$id): JsonResponse{

        try {


            $rules = [
                'exam_type' => 'required|in:full_exam,lesson,subject_class,video'
            ];
            $validator = Validator::make($request->all(), $rules, [
                'exam_type.in' => 407,
            ]);

            if ($validator->fails()) {
                $errors = collect($validator->errors())->flatten(1)[0];
                if (is_numeric($errors)) {

                    $errors_arr = [
                        407 => 'Failed,Exam type must be an lesson or video or subject_class oe full_exam.',
                    ];

                    $code = collect($validator->errors())->flatten(1)[0];
                    return self::returnResponseDataApi(null, isset($errors_arr[$errors]) ? $errors_arr[$errors] : 500, $code);
                }
                return self::returnResponseDataApi(null, $validator->errors()->first(), 422);
            }

            if($request->exam_type == 'video' || $request->exam_type == 'subject_class' || $request->exam_type == 'lesson'){
                $exam = OnlineExam::where('id','=',$id)->first();
                if(!$exam){
                    return self::returnResponseDataApi(null,"هذا الامتحان غير موجود",404,404);
                }

                $exam_degree_depends = ExamDegreeDepends::where('online_exam_id','=',$exam->id)->orderBy('full_degree','DESC')->first();
                $exam_degree_depends->update(['exam_depends' => 'yes']);
                return self::returnResponseDataApi(new OnlineExamResource($exam),"تم اعتماد الدرجه الاعلي لهذا الامتحان",200);

            }else{

                if($request->exam_type == 'full_exam')
                $exam = AllExam::where('id','=',$id)->first();
                if(!$exam){
                    return self::returnResponseDataApi(null,"الامتحان الشامل غير موجود",404,404);
                }
                $exam_degree_depends = ExamDegreeDepends::where('all_exam_id','=',$exam->id)->orderBy('full_degree','DESC')->first();
                $exam_degree_depends->update(['exam_depends' => 'yes']);
                return self::returnResponseDataApi(new AllExamResource($exam),"تم اعتماد الدرجه الاعلي لهذا الامتحان",200);
            }

        }catch (\Exception $exception) {
            return self::returnResponseDataApi(null, $exception->getMessage(), 500);
        }
    }

}
