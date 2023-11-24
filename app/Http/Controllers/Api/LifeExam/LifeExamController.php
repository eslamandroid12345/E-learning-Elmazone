<?php

namespace App\Http\Controllers\Api\LifeExam;

use App\Http\Controllers\Controller;
use App\Http\Resources\LifeExamQuestionsResource;
use App\Http\Resources\LifeExamResource;
use App\Http\Resources\LiveExamResource;
use App\Http\Resources\QuestionResource;
use App\Models\Answer;
use App\Models\Degree;
use App\Models\ExamDegreeDepends;
use App\Models\Lesson;
use App\Models\LifeExam;
use App\Models\OnlineExamUser;
use App\Models\Question;
use App\Models\SubjectClass;
use App\Models\VideoParts;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LifeExamController extends Controller{


    //access first question of life exam
    public function access_first_question($id){

        $life_exam = LifeExam::whereHas('term', function ($term){
            $term->where('status', '=', 'active')->where('season_id','=',auth('user-api')->user()->season_id);
        })->where('season_id','=',auth()->guard('user-api')->user()->season_id)->where('id','=',$id)->first();


        if(!$life_exam){
            return self::returnResponseDataApi(null,"الامتحان الايف غير موجود",404,404);
        }

        $first_question = $life_exam->questions()->orderBy('id','ASC')->first();
        $end =  Carbon::createFromTimeString($life_exam->time_end);
        $first_question->remaining_time = $end->diffInMinutes(Carbon::now()->format('H:i:s'));

        //start check last question of exam
        $life_exam_user = OnlineExamUser::where('life_exam_id','=',$life_exam->id)->where('user_id','=',auth('user-api')->id())->latest()->first();
        if($life_exam_user){
          $access_question = Question::orderBy('id','ASC')->get()->except($life_exam_user->question_id)->where('id','>',$life_exam_user->question_id)->first();
          if($access_question){
              $access_question->remaining_time = $end->diffInMinutes(Carbon::now()->format('H:i:s'));
          }else{
              return self::returnResponseDataApi(null,"لقد انتهيت من اداء الامتحان",202);

          }

        }else{
            $access_question = $first_question;
        }

        return self::returnResponseDataApi(new LifeExamQuestionsResource($access_question),"تم الوصول الي اول سؤال في الامتحان الايف",200);
    }
    //access  live exam
    public function access_live_exam($id){

        $life_exam = LifeExam::whereHas('term', function ($term){
            $term->where('status', '=', 'active')->where('season_id','=',auth('user-api')->user()->season_id);
        })->where('season_id','=',auth()->guard('user-api')->user()->season_id)->where('id','=',$id)->first();


        if(!$life_exam){
            return self::returnResponseDataApi(null,"الامتحان الايف غير موجود",404,404);
        }



        return self::returnResponseDataApi(new LiveExamResource($life_exam),"تم الوصول الي اول سؤال في الامتحان الايف",200);
    }


    public function solve_live_exam_with_student(Request $request,$id){


        $life_exam = LifeExam::whereHas('term', function ($term){
            $term->where('status','=','active')->where('season_id','=',auth('user-api')->user()->season_id);
        })->where('season_id','=',auth()->guard('user-api')->user()->season_id)->where('id','=',$id)->first();

        if(!$life_exam){
            return self::returnResponseDataApi(null,"الامتحان الايف غير موجود",404,404);
        }


        $per = 0;
        $sum_degree_for_user = 0;
        for ($i = 0; $i < count($request->details); $i++) {
            $answer = Answer::where('id', '=', $request->details[$i]['answer'])->first();
            $life_exam_user = OnlineExamUser::create([
                'user_id' => Auth::guard('user-api')->id(),
                'question_id' => $request->details[$i]['question'],
                'answer_id' => $request->details[$i]['answer'],
                'life_exam_id' => $life_exam->id,
                'status' =>  $answer->answer_status == "correct" ? "solved" : "un_correct",
            ]);


            //now
            Degree::create([
                'user_id' => auth()->id(),
                'question_id' => $request->details[$i]['question'],
                'life_exam_id' =>  $life_exam_user->life_exam_id,
                'type' => 'choice',
                'degree' => $life_exam_user->status == "solved" ? $life_exam_user->question->degree : 0,
            ]);

            $degrees_depends = ExamDegreeDepends::where('life_exam_id','=',$id)
                ->where('user_id', '=',auth('user-api')->id());

            $depends = ExamDegreeDepends::where('life_exam_id','=',$id)
                ->where('user_id', '=',auth('user-api')->id())->first();

            if($degrees_depends->exists()){
                $depends->update([
                    'full_degree' =>  $life_exam_user->status == "solved" ?
                        $depends->full_degree+=$life_exam_user->question->degree
                        : $depends->full_degree+=0,
                ]);
            }else{
                ExamDegreeDepends::create([
                    'user_id' => auth('user-api')->id(),
                    'life_exam_id' =>  $life_exam_user->life_exam_id,
                    'full_degree' => $life_exam_user->status == "solved" ? $life_exam_user->question->degree : 0,
                ]);
            }
            $sum_degree_for_user = ExamDegreeDepends::where('life_exam_id','=',$id)
                    ->where('user_id', '=',auth('user-api')->id())->sum('full_degree');
            $per = (($sum_degree_for_user / $life_exam->degree) * 100);
        }

        return response()->json(["data" => null, "message" => "تم الوصول الي حل جميع الاسئلة", "code" => 201, "degree" => (int)$sum_degree_for_user, "per" => $per . "%",]);

    }


    public function add_life_exam_with_student_question_by_question(Request $request,$id){


        $life_exam = LifeExam::whereHas('term', function ($term){
            $term->where('status','=','active')->where('season_id','=',auth('user-api')->user()->season_id);
        })->where('season_id','=',auth()->guard('user-api')->user()->season_id)->where('id','=',$id)->first();

        if(!$life_exam){
            return self::returnResponseDataApi(null,"الامتحان الايف غير موجود",404,404);
        }

        $rules = [
            'question_id' => ['required',Rule::exists('online_exam_questions','question_id')->where(function ($query) use($life_exam) {return $query->where('life_exam_id',$life_exam->id);})],
            'answer_id' => ['required',Rule::exists('answers','id')->where(function ($query) use($request) {return $query->where('question_id',$request->question_id);})],

        ];
        $validator = Validator::make($request->all(), $rules, [
            'question_id.exists' => 406,
            'answer_id.exists' => 407,
        ]);

        if ($validator->fails()) {
            $errors = collect($validator->errors())->flatten(1)[0];
            if (is_numeric($errors)) {

                $errors_arr = [
                    406 => 'هذا السؤال غير تابع لهذا الامتحان',
                    407 => 'الاجابه غير تابعه لهذا السؤال'
                ];

                $code = collect($validator->errors())->flatten(1)[0];
                return self::returnResponseDataApi(null, isset($errors_arr[$errors]) ? $errors_arr[$errors] : 500, $code);
            }
            return self::returnResponseDataApi(null, $validator->errors()->first(), 422);
        }
        /*
         * $startTime = Carbon::parse($this->start_time);
          $finishTime = Carbon::parse($this->finish_time);
          $totalDuration = $finishTime->diffForHumans($startTime);
          dd($totalDuration);
         */

        $answer = Answer::where('id','=',$request->answer_id)->first();
        $online_exam_answer = OnlineExamUser::where('user_id','=',Auth::guard('user-api')->id())
            ->where('question_id','=',$request->question_id)
            ->where('life_exam_id','=',$id)->first();

        if($online_exam_answer){
            return self::returnResponseDataApi(null,"تم حل هذا السؤال من قبل", 202);
        }else{


            $life_exam_user = OnlineExamUser::create([
                'user_id' => Auth::guard('user-api')->id(),
                'question_id' => $request->question_id,
                'answer_id' => $request->answer_id,
                'life_exam_id' => $life_exam->id,
                'status' =>  $answer->answer_status == "correct" ? "solved" : "un_correct",
            ]);


            //now
            Degree::create([
                'user_id' => auth()->id(),
                'question_id' => $request->question_id,
                'life_exam_id' =>  $life_exam_user->life_exam_id,
                'type' => 'choice',
                'degree' => $life_exam_user->status == "solved" ? $life_exam_user->question->degree : 0,
            ]);

            $degrees_depends = ExamDegreeDepends::where('life_exam_id','=',$id)
                ->where('user_id', '=',auth('user-api')->id());

            $depends = ExamDegreeDepends::where('life_exam_id','=',$id)
                ->where('user_id', '=',auth('user-api')->id())->first();

            if($degrees_depends->exists()){
                $depends->update([
                    'full_degree' =>  $life_exam_user->status == "solved" ?
                        $depends->full_degree+=$life_exam_user->question->degree
                        : $depends->full_degree+=0,
                ]);
            }else{
                ExamDegreeDepends::create([
                    'user_id' => auth('user-api')->id(),
                    'life_exam_id' =>  $life_exam_user->life_exam_id,
                    'full_degree' => $life_exam_user->status == "solved" ? $life_exam_user->question->degree : 0,
                ]);
            }


            $next_question = Question::orderBy('id','ASC')->get()->except($request->question_id)->where('id','>',$request->question_id)->first();

            if($next_question){
                $end =  Carbon::createFromTimeString($life_exam->time_end);
                $next_question->remaining_time = $end->diffInMinutes(Carbon::now()->format('H:i:s'));

                return self::returnResponseDataApi(new LifeExamQuestionsResource($next_question),"تم حل السؤال بنجاح",200);
            }else{

               $sum_degree_for_user = ExamDegreeDepends::where('life_exam_id','=',$id)
                    ->where('user_id', '=',auth('user-api')->id())->sum('full_degree');
               $per = (($sum_degree_for_user / $life_exam->degree) * 100);
               if($per >= 65){
                   ExamDegreeDepends::where('life_exam_id','=',$id)
                       ->where('user_id', '=',auth('user-api')->id())->first()->update(['exam_depends' => 'yes']);
               }

               return response()->json(["data" => null, "message" => "تم الوصول الي السؤال الاخير", "code" => 201, "degree" => (int)$sum_degree_for_user, "per" => $per . "%",]);
            }
        }

    }
}
