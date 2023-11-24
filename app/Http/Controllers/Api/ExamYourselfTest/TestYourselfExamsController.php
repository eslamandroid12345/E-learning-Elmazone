<?php

namespace App\Http\Controllers\Api\ExamYourselfTest;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClassesWithLessonsResource;
use App\Http\Resources\ExamQuestionsNewResource;
use App\Http\Resources\LiveExamDetailsResource;
use App\Http\Resources\TestExamQuestionsStudentResource;
use App\Http\Resources\TextYourselfExamResource;
use App\Models\Answer;
use App\Models\ExamDegreeDepends;
use App\Models\ExamsFavorite;
use App\Models\Lesson;
use App\Models\LifeExam;
use App\Models\OnlineExamQuestion;
use App\Models\OnlineExamUser;
use App\Models\Question;
use App\Models\SubjectClass;
use App\Models\TestYourSelfExamQuestions;
use App\Models\TestYourselfExams;
use App\Models\TextExamUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TestYourselfExamsController extends Controller{


   public function makeExam(Request $request){

       try {

           $validator = Validator::make($request->all(), [
               'questions_type' => 'required|in:low,mid,high',
               'lesson_id' => 'nullable|exists:lessons,id',
               'subject_class_id' => 'nullable|exists:subject_classes,id',
               'total_time' => 'required|integer',
               'num_of_questions' => 'required|integer'
           ], [
               'questions_type.in' => 407,
               'lesson_id.exists' => 408,
               'subject_class_id.exists' => 409,

           ]);

           if ($validator->fails()) {
               $errors = collect($validator->errors())->flatten(1)[0];
               if (is_numeric($errors)) {

                   $errors_arr = [
                       407 => 'Failed,The questions type must be low or mid or high',
                       408 => 'Failed,The lesson does not exists',
                       409 => 'Failed,The subject_class does not exists',
                   ];

                   $code = collect($validator->errors())->flatten(1)[0];
                   return self::returnResponseDataApi(null, isset($errors_arr[$errors]) ? $errors_arr[$errors] : 500, $code);
               }
               return self::returnResponseDataApi(null, $validator->errors()->first(), 422);
           }

           if ($request->lesson_id == null && $request->subject_class_id == null) {

               return self::returnResponseDataApi(null, "يجب تسجيل الامتحان تبع درس معين او فصل معين", 422);
           }


           if($request->lesson_id != null){

               $lesson = Lesson::query()
                   ->where('id','=',$request->lesson_id)
                   ->first();

               $questions_count = $lesson->questions()
                   ->where('difficulty','=',$request->questions_type)
                   ->count();

             if ($questions_count == 0){

                   return self::returnResponseDataApi(null, "هذا الدرس لا يوجد به اسئله", 418);

               } elseif($request->num_of_questions > $questions_count){

                 return self::returnResponseDataApi(null, "عدد اسئله الدرس اقل من العدد الذي تريد ان تضيفه للامتحان", 417);

             }else{

                 $lesson_check = Lesson::query()
                     ->where('id','=',$request->lesson_id)
                     ->first();

                 $questions = $lesson_check->questions()
                     ->where('difficulty','=',$request->questions_type)
                     ->limit($request->num_of_questions)
                     ->get();

                 $array = [];

                 foreach ($questions as $question){
                     $array[] = $question->degree;
                 }


                 $makeExam = TestYourselfExams::create([
                     'questions_type' => $request->questions_type,
                     'user_id' => Auth::guard('user-api')->id(),
                     'lesson_id' => $request->lesson_id,
                     'num_of_questions' => $request->num_of_questions,
                     'total_degree' => array_sum($array),
                     'total_time' => $request->total_time,
                 ]);

                 $ids = $questions->pluck('id')->toArray();

                 $makeExam->questions()->attach($ids);

                 if($makeExam->save()) {
                     return self::returnResponseDataApi(new TextYourselfExamResource($makeExam), "تم انشاء امتحان تابع لهذا الدرس بنجاح", 200);
                 }else{
                     return self::returnResponseDataApi(null,"يوجد خطاء بتسجيل البيانات برجاء الرجوع لمطور الباك اند", 500);

                 }
             }//end else condition of lesson


           }elseif ($request->subject_class_id != null){

               $class = SubjectClass::query()
                   ->where('id','=',$request->subject_class_id)
                   ->first();

               $questions_count = $class->questions()
                   ->where('difficulty','=',$request->questions_type)
                   ->count();

              if ($questions_count == 0){

                   return self::returnResponseDataApi(null, "هذا الفصل لا يوجد به اسئله", 418);
               } elseif($request->num_of_questions > $questions_count){

                  return self::returnResponseDataApi(null, "عدد اسئله الفصل اقل من العدد الذي تريد ان تضيفه للامتحان", 417);

              }else{

                  $class_check = SubjectClass::query()
                      ->where('id','=',$request->subject_class_id)
                      ->first();

                  $questions = $class_check->questions()
                      ->where('difficulty','=',$request->questions_type)
                      ->limit($request->num_of_questions)
                      ->get();

                  $array = [];

                  foreach ($questions as $question){
                      $array[] = $question->degree;
                  }


                  $makeExam = TestYourselfExams::create([
                      'questions_type' => $request->questions_type,
                      'user_id' => Auth::guard('user-api')->id(),
                      'subject_class_id' => $request->subject_class_id,
                      'num_of_questions' => $request->num_of_questions,
                      'total_degree' => array_sum($array),
                      'total_time' => $request->total_time,
                  ]);

                  $ids = $questions->pluck('id')->toArray();

                  $makeExam->questions()->attach($ids);

                  if($makeExam->save()) {
                      return self::returnResponseDataApi(new TextYourselfExamResource($makeExam), "تم انشاء امتحان تابع لهذا الفصل بنجاح", 200);
                  }else{
                      return self::returnResponseDataApi(null,"يوجد خطاء بتسجيل البيانات برجاء الرجوع لمطور الباك اند", 500);

                  }
              }
           }


       } catch (\Exception $exception) {

           return self::returnResponseDataApi(null, $exception->getMessage(), 500);
       }

   }


   public function examQuestions($id): JsonResponse{

       $testYourselfExam = TestYourselfExams::find($id);
       if(!$testYourselfExam){

           return self::returnResponseDataApi(null, "الامتحان غير موجود", 404);
       }elseif ($testYourselfExam->user_id != Auth::guard('user-api')->id()){

           return self::returnResponseDataApi(null, "غير مصرح لك بمشاهده تفاصيل هذا الامتحان لانه مسجل من قبل طالب اخر", 403);

       } else{

           return self::returnResponseDataApi(new TextYourselfExamResource($testYourselfExam), "تم جلب عدد الاسئله للامتحان", 200);
       }

   }


   public function allClassesWithLessons(): JsonResponse{

       $subjectClasses = SubjectClass::with('lessons:id,name_ar,name_en,subject_class_id')
           ->whereHas('term', fn(Builder $builder)=>
           $builder->where('status', '=', 'active')
               ->where('season_id','=',auth('user-api')->user()->season_id))
           ->where('season_id','=',auth()->guard('user-api')->user()->season_id)
           ->select('id','name_ar','name_en','image')
           ->get();


       return self::returnResponseDataApi(ClassesWithLessonsResource::collection($subjectClasses),"تم الحصول علي جميع بيانات الفصول والدروس بنجاح",200);
   }


    public function solveExam(Request $request,$id): JsonResponse{


        $testYourselfExam = TestYourselfExams::query()
            ->where('id', '=', $id)
            ->first();


        if (!$testYourselfExam) {
            return self::returnResponseDataApi(null, "الامتحان غير موجود", 404);

        }elseif ($testYourselfExam->user_id != auth('user-api')->id()){

            return self::returnResponseDataApi(null, "غير مصرح لك باداء هذا الامتحان لانه قد يكون مسجل من قبل طالب اخر", 403);

        }else{

            $testYourselfExamStudentCheck = ExamDegreeDepends::query()
                ->where('test_yourself_exam_id', '=', $testYourselfExam->id)
                ->where('user_id', '=', Auth::guard('user-api')->id())
                ->first();

            if ($testYourselfExamStudentCheck) {

                $testExamUserCorrectAnswers = OnlineExamUser::query()
                    ->where('user_id', '=', Auth::guard('user-api')->id())
                    ->where('test_yourself_exam_id', '=',$testYourselfExam->id)
                    ->where('status', '=', 'solved')
                    ->count();

                $testExamUserLeaveAnswers = OnlineExamUser::query()
                    ->where('user_id', '=', Auth::guard('user-api')->id())
                    ->where('test_yourself_exam_id', '=',$testYourselfExam->id)
                    ->where('status', '=', 'leave')
                    ->count();


                $testExamUserMistakeAnswers = OnlineExamUser::query()
                    ->where('user_id', '=', Auth::guard('user-api')->id())
                    ->where('test_yourself_exam_id', '=',$testYourselfExam->id)
                    ->where('status', '=', 'un_correct')
                    ->count();


                $data['per'] = (($testYourselfExamStudentCheck->full_degree / $testYourselfExam->total_degree) * 100) . "%";
                $data['motivational_word'] = "ممتاز بس فيه احسن";
                $data['student_degree'] = ($testYourselfExamStudentCheck->full_degree) . " / " . $testYourselfExam->total_degree;
                $data['num_of_correct_questions'] = $testExamUserCorrectAnswers;
                $data['num_of_mistake_questions'] = $testExamUserMistakeAnswers;
                $data['num_of_leave_questions'] = $testExamUserLeaveAnswers;
                $data['exam_questions'] =  new TestExamQuestionsStudentResource($testYourselfExam);

                return self::returnResponseDataApi($data, "انت اديت هذا الامتحان من قبل", 201);

            } else {

                $total_question_count = TestYourSelfExamQuestions::query()
                    ->where('exam_id','=',$testYourselfExam->id)
                    ->count();

                if(count($request->details) > $total_question_count){

                    return self::returnResponseDataApi(null, "عدد الاسئله االمجاوب عليها اكبر من عدد اسئله الامتحان", 415);

                }else{

                    $arrayOfDegreeTestExam = [];

                    for ($i = 0; $i < count($request->details); $i++) {

                        $question = Question::query()
                            ->where('id', '=', $request->details[$i]['question'])
                            ->first();

                        $answer = Answer::query()
                            ->where('id', '=', $request->details[$i]['answer'])
                            ->first();

                        $testExamStudentCreate = OnlineExamUser::create([
                            'user_id' => Auth::guard('user-api')->id(),
                            'question_id' => $request->details[$i]['question'],
                            'answer_id' => $request->details[$i]['answer'],
                            'test_yourself_exam_id' => $testYourselfExam->id,
                            'status' => $answer != null ? ($answer->answer_status == "correct" ? "solved" : "un_correct") : "leave",
                            'degree' =>  $answer != null ? ($answer->answer_status == "correct" ? $question->degree : 0) : 0,
                        ]);

                        $arrayOfDegreeTestExam[] =  $testExamStudentCreate->degree;
                    }


                    $resultOfDegreeTestExam = ExamDegreeDepends::create([
                        'test_yourself_exam_id' => $testYourselfExam->id,
                        'user_id' => Auth::guard('user-api')->id(),
                        'full_degree' => array_sum($arrayOfDegreeTestExam),
                    ]);


                    $testExamUserCorrectAnswers = OnlineExamUser::query()
                        ->where('user_id', '=', Auth::guard('user-api')->id())
                        ->where('test_yourself_exam_id', '=',$testYourselfExam->id)
                        ->where('status', '=', 'solved')
                        ->count();


                    $testExamUserLeaveAnswers = OnlineExamUser::query()
                        ->where('user_id', '=', Auth::guard('user-api')->id())
                        ->where('test_yourself_exam_id', '=',$testYourselfExam->id)
                        ->where('status', '=', 'leave')
                        ->count();


                    $testExamUserMistakeAnswers = OnlineExamUser::query()
                        ->where('user_id', '=', Auth::guard('user-api')->id())
                        ->where('test_yourself_exam_id', '=',$testYourselfExam->id)
                        ->where('status', '=', 'un_correct')
                        ->count();


                    $data['per'] = (($resultOfDegreeTestExam->full_degree / $testYourselfExam->total_degree) * 100) . "%";
                    $data['motivational_word'] = "ممتاز بس فيه احسن";
                    $data['student_degree'] = ($resultOfDegreeTestExam->full_degree) . " / " . $testYourselfExam->total_degree;
                    $data['num_of_correct_questions'] =  $testExamUserCorrectAnswers;
                    $data['num_of_mistake_questions'] =  $testExamUserMistakeAnswers;
                    $data['num_of_leave_questions'] = $testExamUserLeaveAnswers;
                    $data['exam_questions'] = new TestExamQuestionsStudentResource($testYourselfExam);

                    return self::returnResponseDataApiWithMultipleIndexes($data, "تم اداء الامتحان بنجاح", 200);

                }

            }
        }

    }
}
