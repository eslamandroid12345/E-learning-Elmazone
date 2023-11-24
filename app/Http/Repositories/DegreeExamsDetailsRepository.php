<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\DegreeExamsDetailsRepositoryInterface;
use App\Http\Resources\AllExamDegreeDetailsResource;
use App\Http\Resources\LessonExamDegreeDetailsResource;
use App\Http\Resources\SubjectClassExamDegreeDetailsResource;
use App\Http\Resources\VideoExamDegreeDetailsResource;
use App\Models\AllExam;
use App\Models\ExamDegreeDepends;
use App\Models\Lesson;
use App\Models\OnlineExam;
use App\Models\SubjectClass;
use App\Models\VideoParts;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DegreeExamsDetailsRepository extends ResponseApi implements DegreeExamsDetailsRepositoryInterface{

    public function allExamsDegreeDetails(): JsonResponse
    {

        $allExams = AllExam::AllExamDegreeDetailsForStudent();

        $degreeDependsFullDegree = ExamDegreeDepends::query()
            ->whereHas('all_exam',fn(Builder $builder) =>
            $builder->where('user_id','=',Auth::guard('user-api')->id()))
            ->where('exam_depends','=','yes')
            ->pluck('full_degree')
            ->toArray();


        $degreeOfAllExam = AllExam::query()
            ->whereHas('exam_degree_depends',fn(Builder $builder) =>
            $builder ->where('exam_depends','=','yes')
                ->where('user_id','=',Auth::guard('user-api')->id()))
            ->pluck('degree')
            ->toArray();

        $depends = ExamDegreeDepends::query()
            ->where('all_exam_id', '!=', null)
            ->where('user_id', '=', Auth::guard('user-api')->id())
            ->where('exam_depends', '=', 'yes')
            ->first();

        if(!$depends){

            return self::returnResponseDataApi(null,"يجب دخول امتحان شامل علي الاقل لاظهار درجاتي وتقيماتي",419);

        }else{

            $data['degrees']  = AllExamDegreeDetailsResource::collection($allExams);
            $data['total_per'] =  number_format((array_sum($degreeDependsFullDegree) / array_sum($degreeOfAllExam)) * 100,2);
            $data['motivational_word'] = "ممتاز بس فيه أحسن ";

            return self::returnResponseDataApi($data,"تم الحصول علي جميع درجات الامتحانات الشامله",200);
        }




    }

    public function classDegreeDetails($id): JsonResponse
    {

        if($id != 0){
            $class = SubjectClass::find($id);
            if(!$class){
                return self::returnResponseDataApi(null,"هذا الفصل غير موجود",404);
            }

            $degreeDependsFullDegree = ExamDegreeDepends::query()
                ->whereHas('online_exam',fn(Builder $builder) =>
                $builder->where('class_id','=',$class->id))
                ->where('user_id','=',Auth::guard('user-api')->id())
                ->where('exam_depends','=','yes')
                ->pluck('full_degree')
                ->toArray();

            $degreeOfAllExam = OnlineExam::query()
                ->whereHas('exam_degree_depends',fn(Builder $builder) =>
                $builder ->where('exam_depends','=','yes')
                    ->where('user_id','=',Auth::guard('user-api')->id()))
                ->whereHas('class',fn(Builder $builder) =>
                $builder->where('class_id','=',$class->id))
                ->pluck('degree')
                ->toArray();


            $depends = ExamDegreeDepends::query()
                ->whereHas('online_exam',fn(Builder $builder) =>
                $builder->where('class_id','=',$class->id))
                ->where('user_id','=',Auth::guard('user-api')->id())
                ->where('exam_depends','=','yes')
                ->first();

            if(!$depends){

                return self::returnResponseDataApi(null,"يجب دخول هذا الفصل لاظهار درجاتي وتقيماتي",419);

            }else{

                $onlineExams =  OnlineExam::OnlineExamSubjectClassDegreeDetails($class);
                $data['degrees']  = SubjectClassExamDegreeDetailsResource::collection($onlineExams);
                $data['total_per'] =  number_format((array_sum($degreeDependsFullDegree) / array_sum($degreeOfAllExam)) * 100,2);
                $data['motivational_word'] = "ممتاز بس فيه أحسن ";
                return self::returnResponseDataApi($data,"تم الحصول علي جميع درجات امتحانات هذا الفصل للطالب",200);
            }




        }else{

            $idsOfClasses = SubjectClass::query()
                ->pluck('id')
                ->toArray();


            $degreeDependsFullDegree = ExamDegreeDepends::query()
                ->whereHas('online_exam')
                ->where('user_id','=',Auth::guard('user-api')->id())
                ->where('exam_depends','=','yes')
                ->whereHas('online_exam', fn(Builder $builder)=>
                $builder->where('type','=','class')
                )
                ->pluck('full_degree')
                ->toArray();

            $degreeOfAllExam = OnlineExam::query()
                ->whereHas('exam_degree_depends',fn(Builder $builder) =>
                $builder ->where('exam_depends','=','yes')
                    ->where('user_id','=',Auth::guard('user-api')->id()))
                ->whereHas('class')
                ->pluck('degree')
                ->toArray();

            $depends = ExamDegreeDepends::query()
                ->whereHas('online_exam')
                ->where('user_id','=',Auth::guard('user-api')->id())
                ->where('exam_depends','=','yes')
                ->whereHas('online_exam', fn(Builder $builder)=>
                $builder->where('type','=','class')
                )
                ->first();

            if(!$depends){

                return self::returnResponseDataApi(null,"يجب دخول فصل واحد علي الاقل لاظهار درجاتي وتقيماتي",419);

            }else{

                $onlineExams =  OnlineExam::OnlineExamSubjectClasses($idsOfClasses);
                $data['degrees']  = SubjectClassExamDegreeDetailsResource::collection($onlineExams);
                $data['total_per'] =  number_format((array_sum($degreeDependsFullDegree) / array_sum($degreeOfAllExam)) * 100,2);
                $data['motivational_word'] = "ممتاز بس فيه أحسن ";
                return self::returnResponseDataApi($data,"تم الحصول علي جميع درجات امتحانات هذا الفصل للطالب",200);
            }



        }

    }


    public function videosByLessonDegreeDetails($id): JsonResponse{


        if($id != 0){
            $lesson = Lesson::find($id);
            if(!$lesson){
                return self::returnResponseDataApi(null,"هذا الدرس غير موجود",404);
            }


            $degreeDependsFullDegree = ExamDegreeDepends::query()
                ->whereHas('online_exam',fn(Builder $builder) =>
                $builder->whereHas('video',fn(Builder $builder)=>
                $builder->where('lesson_id','=',$lesson->id)
                ))->where('user_id','=',Auth::guard('user-api')->id())
                ->where('exam_depends','=','yes')
                ->pluck('full_degree')
                ->toArray();


            $degreeOfAllExam = OnlineExam::query()
                ->whereHas('video',fn(Builder $builder)=>
                $builder->where('lesson_id','=',$lesson->id)
                )->whereHas('exam_degree_depends',fn(Builder $builder) =>
                $builder ->where('exam_depends','=','yes')
                    ->where('user_id','=',Auth::guard('user-api')->id()))
                ->pluck('degree')
                ->toArray();



            $depends = ExamDegreeDepends::query()
                ->whereHas('online_exam',fn(Builder $builder) =>
                $builder->whereHas('video',fn(Builder $builder)=>
                $builder->where('lesson_id','=',$lesson->id)
                ))->where('user_id','=',Auth::guard('user-api')->id())
                ->where('exam_depends','=','yes')
                ->first();

            if(!$depends){

                return self::returnResponseDataApi(null,"يجب دخول امتحان فيديو واحد لهذا الدرس لاظهار درجاتي وتقيماتي",419);

            }else{

                $onlineExams =  OnlineExam::OnlineExamLessonVideosDegreeDetails($lesson);
                $data['degrees']  = VideoExamDegreeDetailsResource::collection($onlineExams);
                $data['total_per'] =  number_format((array_sum($degreeDependsFullDegree) / array_sum($degreeOfAllExam)) * 100,2);
                $data['motivational_word'] = "ممتاز بس فيه أحسن ";
                return self::returnResponseDataApi($data,"تم الحصول علي جميع درجات امتحانات فيديوهات الشرح لهذا الدرس بنجاح",200);
            }




        }else{


            $idsVideos = VideoParts::query()
                ->pluck('id')
                ->toArray();

            $degreeDependsFullDegree = ExamDegreeDepends::query()
                ->whereHas('online_exam',fn(Builder $builder) =>
                $builder->whereHas('video'))
                ->where('user_id','=',Auth::guard('user-api')->id())
                ->where('exam_depends','=','yes')
                ->whereHas('online_exam', fn(Builder $builder)=>

                $builder->where('type','=','video')
                )
                ->pluck('full_degree')
                ->toArray();


            $degreeOfAllExam = OnlineExam::query()
                ->whereHas('video')
                ->whereHas('exam_degree_depends',fn(Builder $builder) =>
                $builder ->where('exam_depends','=','yes')
                    ->where('user_id','=',Auth::guard('user-api')->id()))
                ->pluck('degree')
                ->toArray();




            $depends = ExamDegreeDepends::query()
                ->whereHas('online_exam',fn(Builder $builder) =>
                $builder->whereHas('video'))
                ->where('user_id','=',Auth::guard('user-api')->id())
                ->where('exam_depends','=','yes')
                ->whereHas('online_exam', fn(Builder $builder)=>

                $builder->where('type','=','video')
                )->first();

            if(!$depends){

                return self::returnResponseDataApi(null,"يجب دخول امتحان فيديو واحد علي الاقل لاظهار درجاتي وتقيماتي",419);

            }else {
                $onlineExams = OnlineExam::onlineExamLessons($idsVideos);
                $data['degrees'] = VideoExamDegreeDetailsResource::collection($onlineExams);
                $data['total_per'] = number_format((array_sum($degreeDependsFullDegree) / array_sum($degreeOfAllExam)) * 100, 2);
                $data['motivational_word'] = "ممتاز بس فيه أحسن ";
                return self::returnResponseDataApi($data, "تم الحصول علي جميع درجات امتحانات فيديوهات الشرح ", 200);

            }
        }

    }

    public function lessonDegreeDetails($id): JsonResponse
    {

        if($id != 0) {

            $lesson = Lesson::find($id);
            if(!$lesson){
                return self::returnResponseDataApi(null,"هذا الدرس غير موجود",404);
            }


            $degreeDependsFullDegree = ExamDegreeDepends::query()
                ->whereHas('online_exam',fn(Builder $builder) =>
                $builder->where('lesson_id','=',$lesson->id))
                ->where('user_id','=',Auth::guard('user-api')->id())
                ->where('exam_depends','=','yes')
                ->pluck('full_degree')
                ->toArray();


            $degreeOfAllExam = OnlineExam::query()
                ->whereHas('exam_degree_depends',fn(Builder $builder) =>
                $builder ->where('exam_depends','=','yes')
                    ->where('user_id','=',Auth::guard('user-api')->id()))
                ->whereHas('lesson',fn(Builder $builder) =>
                $builder->where('lesson_id','=',$lesson->id))
                ->pluck('degree')
                ->toArray();




            $depends = ExamDegreeDepends::query()
                ->whereHas('online_exam',fn(Builder $builder) =>
                $builder->where('lesson_id','=',$lesson->id))
                ->where('user_id','=',Auth::guard('user-api')->id())
                ->where('exam_depends','=','yes')
                ->first();


            if(!$depends){

                return self::returnResponseDataApi(null,"يجب دخول هذا الدرس لاظهار درجاتي وتقيماتي",419);

            }else {
                $onlineExams = OnlineExam::OnlineExamLessonDegreeDetails($lesson);
                $data['degrees'] = LessonExamDegreeDetailsResource::collection($onlineExams);
                $data['total_per'] = number_format((array_sum($degreeDependsFullDegree) / array_sum($degreeOfAllExam)) * 100, 2);
                $data['motivational_word'] = "ممتاز بس فيه أحسن ";

                return self::returnResponseDataApi($data, "تم الحصول علي جميع درجات امتحانات الدرس بنجاح", 200);

            }

        }else{

            $lessonsIds = Lesson::query()
                ->pluck('id')
                ->toArray();

            $degreeDependsFullDegree = ExamDegreeDepends::query()
                ->whereHas('online_exam')
                ->where('user_id','=',Auth::guard('user-api')->id())
                ->where('exam_depends','=','yes')
                ->whereHas('online_exam', fn(Builder $builder)=>

                $builder->where('type','=','lesson')
                )

                ->pluck('full_degree')
                ->toArray();


            $degreeOfAllExam = OnlineExam::query()
                ->whereHas('exam_degree_depends',fn(Builder $builder) =>
                $builder ->where('exam_depends','=','yes')
                    ->where('user_id','=',Auth::guard('user-api')->id()))
                ->whereHas('lesson')
                ->pluck('degree')
                ->toArray();


            $depends = ExamDegreeDepends::query()
                ->whereHas('online_exam')
                ->where('user_id','=',Auth::guard('user-api')->id())
                ->where('exam_depends','=','yes')
                ->whereHas('online_exam', fn(Builder $builder)=>

                $builder->where('type','=','lesson')
                )->first();

            if(!$depends){

                return self::returnResponseDataApi(null,"يجب دخول درس واحد علي الاقل لاظهار درجاتي وتقيماتي",419);

            }else {

                $onlineExams = OnlineExam::onlineExamAllLessons($lessonsIds);
                $data['degrees'] = LessonExamDegreeDetailsResource::collection($onlineExams);
                $data['total_per'] = number_format((array_sum($degreeDependsFullDegree) / array_sum($degreeOfAllExam)) * 100, 2);
                $data['motivational_word'] = "ممتاز بس فيه أحسن ";

                return self::returnResponseDataApi($data, "تم الحصول علي جميع درجات امتحانات الدروس", 200);

            }
        }

    }

}
