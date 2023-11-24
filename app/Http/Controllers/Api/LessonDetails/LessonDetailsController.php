<?php

namespace App\Http\Controllers\Api\LessonDetails;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExamQuestionsNewResource;
use App\Http\Resources\OnlineExamNewResource;
use App\Http\Resources\VideoPartOnlineExamsResource;
use App\Http\Resources\VideoUploadFileDetailsResource;
use App\Http\Resources\VideoPartDetailsNewResource;
use App\Models\ExamDegreeDepends;
use App\Models\Lesson;
use App\Models\OnlineExam;
use App\Models\OnlineExamUser;
use App\Models\TextExamUser;
use App\Models\Timer;
use App\Models\VideoFilesUploads;
use App\Models\VideoParts;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonDetailsController extends Controller{


    public function allVideoByLessonId($id): JsonResponse{

        $lesson = Lesson::query()->where('id','=',$id)->first();
        if(!$lesson){

            return self::returnResponseDataApi(null,"هذا الدرس غير موجود",404,404);
        }

        $videos = VideoParts::query()->where('lesson_id','=',$lesson->id)->get();

        return self::returnResponseDataApi(VideoPartDetailsNewResource::collection($videos),"تم الحصول علي جميع فيديوهات الشرح بنجاح",200);

    }

    public function allPdfByVideoId($id): JsonResponse{

        $video = VideoParts::query()
        ->where('id','=',$id)->first();
        if(!$video){

            return self::returnResponseDataApi(null,"هذا الفيديو غير موجود",404,404);
        }

        $allPdf = VideoFilesUploads::query()
            ->where('video_part_id','=',$video->id)
            ->where('file_type','=','pdf')
            ->get();

        if(!$allPdf){

            return self::returnResponseDataApi(null,"لا يوجد اي ملفات ورقيه تابعه لهذا الفيديو",404,404);

        }
        return self::returnResponseDataApi(VideoUploadFileDetailsResource::collection($allPdf),"تم الحصول علي جميع ملخصات الشرح بنجاح",200);

    }


    public function allAudiosByVideoId($id): JsonResponse{

        $video = VideoParts::query()
        ->where('id','=',$id)->first();

        if(!$video){

            return self::returnResponseDataApi(null,"هذا الفيديو غير موجود",404,404);
        }

        $allAudios = VideoFilesUploads::query()
            ->where('video_part_id','=',$video->id)
            ->where('file_type','=','audio')
            ->get();

        if(!$allAudios){

            return self::returnResponseDataApi(null,"لا يوجد اي ملفات صوتيه تابعه لهذا الفيديو",404,404);

        }
        return self::returnResponseDataApi(VideoUploadFileDetailsResource::collection($allAudios),"تم الحصول علي جميع الملفات الصوتيه بنجاح",200);


    }

      public function allExamsByVideoId($id): JsonResponse{

          $video = VideoParts::query()->where('id','=',$id)->first();

          if(!$video){
              return self::returnResponseDataApi(null,"هذا الفيديو غير موجود",404,404);
          }else{

              $allExams = OnlineExam::query()->where('video_id','=',$video->id)->first();

              if(!$allExams){

                  return self::returnResponseDataApi(null,"لا يوجد اي واجب تابع لهذ الفيديو",404,404);

              }

              return self::returnResponseDataApi(new VideoPartOnlineExamsResource($allExams),"تم الحصول علي امتحان الفيديو بنجاح",200);

          }


      }

    public function allExamsByLessonId($id): JsonResponse{

        $lesson = Lesson::query()->where('id','=',$id)->first();
        if(!$lesson){

            return self::returnResponseDataApi(null,"هذا الدرس غير موجود",404,404);
        }

        $allExams = OnlineExam::query()->where('lesson_id','=',$lesson->id)->get();

        if(!$allExams){

            return self::returnResponseDataApi(null,"لا يوجد اي امتحانات لهذا الدرس",404,404);

        }

        return self::returnResponseDataApi(OnlineExamNewResource::collection($allExams),"تم الحصول علي جميع امتحانات الدرس بنجاح",200);

    }

    //Details of student exam
    public function examDetailsByExamId($id): JsonResponse{


        $video = VideoParts::query()
            ->where('id','=',$id)
            ->first();

        if(!$video){
            return self::returnResponseDataApi(null,"فيديو الشرح غير موجود",404,404);
        }

        $exam = OnlineExam::query()->where('video_id','=',$video->id)->first();

        if(!$exam){
            return self::returnResponseDataApi(null,"هذا الامتحان غير موجود",404,404);
        }

        $degree = ExamDegreeDepends::query()
            ->where('user_id','=',Auth::guard('user-api')->id())
                  ->where('online_exam_id','=',$exam->id)
                   ->where('exam_depends','=','yes')
                  ->first();


        if(!$degree){
            return self::returnResponseDataApi(null,"يجب انت تمتحن اولا لاظهار تقيمك في الامتحان",201);

        }elseif ($degree->exam_depends == 'no'){

            return self::returnResponseDataApi(null,"يجب اعتماد درجه الامتحان لاظهار ادائك في الامتحان",202);

        }else{

            $onlineExamUserCorrectAnswers  = OnlineExamUser::query()
                ->where('user_id','=',Auth::guard('user-api')->id())
                ->where('online_exam_id','=',$exam->id)
                ->where('timer_id', '=',$degree->timer_id)
                ->where('status','=','solved')
                ->count();

            $onlineExamUserMistakeAnswers  = OnlineExamUser::query()
                ->where('user_id','=',Auth::guard('user-api')->id())
                ->where('online_exam_id','=',$exam->id)
                ->where('timer_id', '=',$degree->timer_id)
                ->where('status','=','un_correct')
                ->count();

            $onlineExamUserLeaveAnswers  = OnlineExamUser::query()
                ->where('user_id','=',Auth::guard('user-api')->id())
                ->where('online_exam_id','=',$exam->id)
                ->where('timer_id', '=',$degree->timer_id)
                ->where('status','=','leave')
                ->count();

            $tryingNumber = Timer::query()
                ->where('user_id','=',Auth::guard('user-api')->id())
                ->where('online_exam_id','=',$exam->id)
                ->count();


            $data['full_degree']     = ($degree->full_degree) . " / " . $exam->degree;
            $data['motivational_word'] = "ممتاز بس فيه أحسن ";
            $data['correct_numbers'] =  $onlineExamUserCorrectAnswers;
            $data['mistake_numbers'] =  ($onlineExamUserMistakeAnswers + $onlineExamUserLeaveAnswers);
            $data['trying_numbers']  =  $tryingNumber;
            $data['exam_questions']  = new ExamQuestionsNewResource($exam);

            return self::returnResponseDataApiWithMultipleIndexes($data,"تم الحصول علي تفاصيل الامتحان بنجاح",200);

        }


    }

}
