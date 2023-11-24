<?php

namespace App\Http\Controllers\Api\LiveExam;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChooseLiveExamResource;
use App\Http\Resources\ExamQuestionsNewResource;
use App\Http\Resources\HeroesExamResource;
use App\Http\Resources\LifeExamResource;
use App\Http\Resources\LiveExamDetailsResource;
use App\Http\Resources\LiveExamFavoriteResource;
use App\Http\Resources\LiveExamHeroesResource;
use App\Http\Resources\LiveExamQuestionsResource;
use App\Http\Resources\OnlineExamQuestionResource;
use App\Models\AllExam;
use App\Models\Answer;
use App\Models\ExamDegreeDepends;
use App\Models\LifeExam;
use App\Models\OnlineExam;
use App\Models\OnlineExamUser;
use App\Models\Question;
use App\Models\Timer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LiveExamController extends Controller
{


    public function allOfQuestions($id): JsonResponse
    {

        try {


            $liveExam = LifeExam::query()
                ->whereHas('term', fn(Builder $builder) =>
                $builder->where('status', '=', 'active')
                    ->where('season_id', '=', auth('user-api')->user()->season_id))
                ->where('season_id', '=', auth()->guard('user-api')->user()->season_id)
                ->where('id', '=', $id)
                ->first();

            if (!$liveExam) {
                return self::returnResponseDataApi(null, "الامتحان الايف غير موجود", 404);
            } else {

                return self::returnResponseDataApi(new LiveExamQuestionsResource($liveExam), "تم ارسال جميع الاسئله بالاجابات التابعه لهذا الامتحان", 200);
            }

        } catch (\Exception $exception) {

            return self::returnResponseDataApi(null, $exception->getMessage(), 500);
        }

    }

    public function addLiveExamByStudent(Request $request, $id): JsonResponse
    {

        $liveExam = LifeExam::query()
            ->whereHas('term', fn(Builder $builder) =>
            $builder->where('status', '=', 'active')
                ->where('season_id', '=', auth('user-api')->user()->season_id))
            ->where('season_id', '=', auth()->guard('user-api')->user()->season_id)
            ->where('id', '=', $id)
            ->first();


        if (!$liveExam) {
            return self::returnResponseDataApi(null, "الامتحان الايف غير موجود", 404);
        }

        $liveExamStudentCheck = ExamDegreeDepends::query()
            ->where('life_exam_id', '=', $liveExam->id)
            ->where('user_id', '=', Auth::guard('user-api')->id())
            ->first();

        if ($liveExamStudentCheck) {

            $liveExamUserCorrectAnswers = OnlineExamUser::query()
                ->where('user_id', '=', Auth::guard('user-api')->id())
                ->where('life_exam_id', '=', $liveExam->id)
                ->where('status', '=', 'solved')
                ->count();


            $liveExamUserMistakeAnswers = OnlineExamUser::query()
                ->where('user_id', '=', Auth::guard('user-api')->id())
                ->where('life_exam_id', '=', $liveExam->id)
                ->where('status', '=', 'un_correct')
                ->count();


            $numOfLeaveQuestions = OnlineExamUser::query()
                ->where('user_id', Auth::guard('user-api')->id())
                ->where('life_exam_id', '=', $liveExam->id)
                ->where('status', '=', 'leave')
                ->count();


            $allOfStudentEnterLifeExam = User::with(['exam_degree_depends_user' => fn(HasOne $q) =>
            $q->where('life_exam_id', '=', $liveExam->id)])
                ->whereHas('exam_degree_depends_user', fn(Builder $builder) =>
                $builder->where('life_exam_id', '=', $liveExam->id))
                ->pluck('id')
                ->toArray();


            $checkStudentAuth = Auth::guard('user-api')->user();
            $studentAuth = new LiveExamHeroesResource($checkStudentAuth);
            $studentAuth->ordered = (array_search($checkStudentAuth->id, $allOfStudentEnterLifeExam)) + 1;


            $data['ordered'] = $studentAuth->ordered;
            $data['motivational_word'] = "ممتاز بس فيه أحسن ";
            $data['student_per'] = (($liveExamStudentCheck->full_degree / $liveExam->degree) * 100) . "%";
            $data['num_of_correct_questions'] = $liveExamUserCorrectAnswers;
            $data['num_of_mistake_questions'] = $liveExamUserMistakeAnswers;
            $data['num_of_leave_questions'] = $numOfLeaveQuestions;
            $data['exam_questions'] = new ExamQuestionsNewResource($liveExam);

            return self::returnResponseDataApi($data, "انت اديت هذا الامتحان من قبل", 201,201);

        } else {

            DB::beginTransaction();

            try {

                $arrayOfDegree = [];

                for ($i = 0; $i < count($request->details); $i++) {

                    $question = Question::query()
                        ->where('id', '=', $request->details[$i]['question'])
                        ->first();

                    $answer = Answer::query()
                        ->where('id', '=', $request->details[$i]['answer'])
                        ->first();

                    $examStudentCreate = OnlineExamUser::create([
                        'user_id' => Auth::guard('user-api')->id(),
                        'question_id' => $request->details[$i]['question'],
                        'answer_id' => $request->details[$i]['answer'],
                        'life_exam_id' => $liveExam->id,
                        'status' => $request->details[$i]['answer'] == null ? "leave"  : ($answer->answer_status == "correct" ? "solved" : "un_correct"),
                        'degree' => $request->details[$i]['answer'] == null ? 0 : ($answer->answer_status == "correct" ? $question->degree : 0) ,
                    ]);

                    $arrayOfDegree[] = $examStudentCreate->degree;
                }


                $examDegreeDepends = ExamDegreeDepends::create([
                    'life_exam_id' => $liveExam->id,
                    'user_id' => Auth::guard('user-api')->id(),
                    'full_degree' => array_sum($arrayOfDegree),
                ]);


                $liveExamUserCorrectAnswers = OnlineExamUser::query()
                    ->where('user_id', '=', Auth::guard('user-api')->id())
                    ->where('life_exam_id', '=', $liveExam->id)
                    ->where('status', '=', 'solved')
                    ->count();

                $liveExamUserMistakeAnswers = OnlineExamUser::query()
                    ->where('user_id', '=', Auth::guard('user-api')->id())
                    ->where('life_exam_id', '=', $liveExam->id)
                    ->where('status','=','un_correct')
                    ->count();


                $numOfLeaveQuestions = OnlineExamUser::query()
                    ->where('user_id', Auth::guard('user-api')->id())
                    ->where('life_exam_id', '=', $liveExam->id)
                    ->where('status', '=', 'leave')
                    ->count();

                $allOfStudentEnterLifeExam = User::with(['exam_degree_depends_user' => fn(HasOne $q) =>
                $q->where('life_exam_id', '=', $liveExam->id)])
                    ->whereHas('exam_degree_depends_user', fn(Builder $builder) =>
                    $builder->where('life_exam_id', '=', $liveExam->id))
                    ->pluck('id')
                    ->toArray();

                $checkStudentAuth = Auth::guard('user-api')->user();
                $studentAuth = new LiveExamHeroesResource($checkStudentAuth);
                $studentAuth->ordered = (array_search($checkStudentAuth->id, $allOfStudentEnterLifeExam)) + 1;


                $data['ordered'] = $studentAuth->ordered;
                $data['motivational_word'] = "ممتاز بس فيه أحسن ";
                $data['student_per'] = (($examDegreeDepends->full_degree / $liveExam->degree) * 100) . "%";
                $data['num_of_correct_questions'] = $liveExamUserCorrectAnswers;
                $data['num_of_mistake_questions'] = $liveExamUserMistakeAnswers;
                $data['num_of_leave_questions'] = $numOfLeaveQuestions;
                $data['exam_questions'] = new LiveExamDetailsResource($liveExam);

                DB::commit();

                return self::returnResponseDataApi($data, "تم اداء الامتحان بنجاح", 200);

            }catch (\Exception $exception){

                DB::rollback();

                return self::returnResponseDataApi(null, "يوجد خطاء ما في السيرفر وحركه تسجيل بيانات الامتحان الالايف لم تسجل في سجل قاعده البيانات", 500,500);

            }
        }

    }


    public function allOfLiveExamsStudent(): JsonResponse
    {

        $allOfLiveExams = LifeExam::query()
            ->whereHas('term', fn(Builder $builder) =>
            $builder->where('status', '=', 'active')
                ->where('season_id', '=', auth('user-api')->user()->season_id))
            ->where('season_id', '=', auth()->guard('user-api')->user()->season_id)
            ->whereHas('exams_degree_depends', fn(Builder $builder) =>
            $builder->where('user_id', '=', Auth::guard('user-api')->id()))
            ->get();

        return self::returnResponseDataApi(LiveExamFavoriteResource::collection($allOfLiveExams), "تم جلب جميع الامتحانات الايف  بنجاح", 200);


    }


    public function choose_live_exam(): JsonResponse
    {

        $allOfLiveExams = LifeExam::query()
            ->whereHas('term', fn(Builder $builder) =>
            $builder->where('status', '=', 'active')
                ->where('season_id', '=', auth('user-api')->user()->season_id))
            ->where('season_id', '=', auth()->guard('user-api')->user()->season_id)
            ->whereHas('exams_degree_depends', fn(Builder $builder) =>
            $builder->where('user_id', '=', Auth::guard('user-api')->id()))
            ->get();

        return self::returnResponseDataApi(ChooseLiveExamResource::collection($allOfLiveExams), "تم جلب جميع الامتحانات الايف  بنجاح", 200);


    }


    public function allOfExamHeroes($id): JsonResponse
    {

        try {


            $liveExamHeroes = LifeExam::query()
                ->whereHas('term', fn(Builder $builder) => $builder->where('status', '=', 'active')
                    ->where('season_id', '=', auth('user-api')->user()->season_id))
                ->where('season_id', '=', auth()->guard('user-api')->user()->season_id)
                ->where('id', '=',$id)
                ->first();

            if (!$liveExamHeroes) {
                return self::returnResponseDataApi(null, "الامتحان الايف غير موجود", 404);
            }

            $liveExmDegreeCheck = ExamDegreeDepends::query()
                ->where('user_id', '=', Auth::guard('user-api')->id())
                ->where('life_exam_id', '=', $liveExamHeroes->id)
                ->first();


            if ($liveExmDegreeCheck) {

                $data['MyOrdered'] = $this->getStudentExamHero($liveExamHeroes->id,$liveExamHeroes->degree);
                $data['AllExamHeroes'] =  $this->getAllExamHeroes($liveExamHeroes->id,$liveExamHeroes->degree);

                return self::returnResponseDataApi($data, "تم الحصول علي ابطال الامتحانات الايف بنجاح", 200);

            } else {

                return self::returnResponseDataApi(null, "انت لم تؤدي هذا الامتحان برجاء الامتحان اولا لاظهار ابطال المنصه", 403);
            }


        } catch (\Exception $exception) {

            return self::returnResponseDataApi(null, $exception->getMessage(), 500);
        }

    }

    public function resultOfLiveExam($id): JsonResponse
    {


        $liveExam = LifeExam::query()
            ->whereHas('term', fn(Builder $builder) => $builder->where('status', '=', 'active')
                ->where('season_id', '=', auth('user-api')->user()->season_id))
            ->where('season_id', '=', auth()->guard('user-api')->user()->season_id)
            ->where('id', '=', $id)
            ->first();

        if (!$liveExam) {
            return self::returnResponseDataApi(null, "الامتحان الايف غير موجود", 404);
        }


        $liveExamStudentCheck = ExamDegreeDepends::query()
            ->where('life_exam_id', '=', $liveExam->id)
            ->where('user_id', '=', Auth::guard('user-api')->id())
            ->first();

        if ($liveExamStudentCheck) {


            $liveExamUserCorrectAnswers = OnlineExamUser::query()
                ->where('user_id', '=', Auth::guard('user-api')->id())
                ->where('life_exam_id', '=', $liveExam->id)
                ->where('status', '=', 'solved')
                ->count();


            $liveExamUserMistakeAnswers = OnlineExamUser::query()
                ->where('user_id', '=', Auth::guard('user-api')->id())
                ->where('life_exam_id', '=', $liveExam->id)
                ->where('status', '=', 'un_correct')
                ->count();


            $allOfStudentEnterLifeExam = User::with(['exam_degree_depends_user' => fn(HasOne $q) =>
            $q->where('life_exam_id', '=', $liveExam->id)])
                ->whereHas('exam_degree_depends_user', fn(Builder $builder) =>
                $builder->where('life_exam_id', '=', $liveExam->id))
                ->pluck('id')
                ->toArray();


            $checkStudentAuth = Auth::guard('user-api')->user();
            $studentAuth = new LiveExamHeroesResource($checkStudentAuth);
            $studentAuth->ordered = (array_search($checkStudentAuth->id, $allOfStudentEnterLifeExam)) + 1;


            $data['student_degree'] = ($liveExamStudentCheck->full_degree) . " / " . $liveExam->degree;
            $data['motivational_word'] = "ممتاز بس فيه أحسن ";
            $data['num_of_correct_questions'] = $liveExamUserCorrectAnswers;
            $data['num_of_mistake_questions'] = $liveExamUserMistakeAnswers;
            $data['ordered'] = $studentAuth->ordered;
            $data['exam_questions'] = new ExamQuestionsNewResource($liveExam);


            return self::returnResponseDataApi($data, "انت اديت هذا الامتحان من قبل", 201);

        } else {

            return self::returnResponseDataApi(null, "انت لم تؤدي هذا الامتحان برجاء اداء الامتحان اولا لاظهار النتيجه", 403);

        }
    }


    final public function getStudentExamHero($live_exam_id,$degree_of_exam): array
    {


        $student = User::query()
            ->where('id','=',auth('user-api')->id())
            ->with(['exam_degree_depends_user' => function($q) use($live_exam_id){
            $q->where('life_exam_id', '=', $live_exam_id);
        }])->whereHas('exam_degree_depends_user', fn(Builder $builder) =>
        $builder->where('life_exam_id', '=', $live_exam_id))
            ->whereHas('season', fn(Builder $builder) =>
            $builder->where('season_id', '=', auth()->guard('user-api')->user()->season_id))
            ->first();


          $heroesDaysIds = collect($this->getAllExamHeroes($live_exam_id,$degree_of_exam))->pluck('id')->toArray();


            $authStudent = Auth::guard('user-api')->user();
            $authData['id'] = $authStudent->id;
            $authData['name'] = $authStudent->name;
            $authData['country'] = lang() == 'ar'? $authStudent->country->name_ar : $authStudent->country->name_en;
            $authData['ordered'] = (array_search($authStudent->id,$heroesDaysIds)) + 1;
            $authData['student_total_degrees'] =  (int)$student->exam_degree_depends_user->full_degree;
            $authData['exams_total_degree'] = (int)$degree_of_exam;
            $authData['image'] = $authStudent->image != null ? asset('/users/'.$authStudent->image) : asset('/default/avatar2.jfif');
            $authData['student_per'] = (($student->exam_degree_depends_user->full_degree / $degree_of_exam) * 100) . "%";

            return $authData;

    }


    final public function getAllExamHeroes($live_exam_id,$degree_of_exam): array
    {

        $students =   User::with(['exam_degree_depends_user' => function($q) use($live_exam_id){
            $q->where('life_exam_id', '=', $live_exam_id)
                ->orderBy('full_degree', 'desc');
        }])->whereHas('exam_degree_depends_user', fn(Builder $builder) =>
        $builder->where('life_exam_id', '=', $live_exam_id)
            ->orderBy('full_degree', 'desc'))
            ->whereHas('season', fn(Builder $builder) =>
            $builder->where('season_id', '=', auth()->guard('user-api')->user()->season_id))
            ->take(10)
            ->get()
            ->sortByDesc('exam_degree_depends_user.full_degree');


        $listOfStudentsIds = $students->pluck('id')->toArray();


              $data = [];
              foreach ($students as $student){

                $studentsData['id'] = $student->id;
                $studentsData['name'] = $student->name;
                $studentsData['country'] = lang() == 'ar'?$student->country->name_ar : $student->country->name_en;
                $studentsData['ordered'] = (array_search($student->id,$listOfStudentsIds)) + 1;
                $studentsData['student_total_degrees'] = (int)$student->exam_degree_depends_user->full_degree;
                $studentsData['exams_total_degree'] = (int)$degree_of_exam;
                $studentsData['image'] = $student->image != null ? asset('/users/'.$student->image) : asset('/default/avatar2.jfif');
                $studentsData['student_per'] = (($student->exam_degree_depends_user->full_degree / $degree_of_exam) * 100) . "%";

                  $data[] = $studentsData;
            }

            return $data;
    }


}
