<?php

namespace App\Http\Controllers\Api\ExamEntry;
use App\Http\Controllers\Controller;
use App\Http\Resources\AllExamHeroesNewResource;
use App\Http\Resources\OnlineExamQuestionResource;
use App\Http\Resources\StudentHeroResource;
use App\Models\AllExam;
use App\Models\Answer;
use App\Models\ExamDegreeDepends;
use App\Models\OnlineExam;
use App\Models\OnlineExamUser;
use App\Models\Question;
use App\Models\TextExamUser;
use App\Models\Timer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ExamEntryController extends Controller
{

    public function all_questions_by_online_exam($id): JsonResponse
    {

        try {

            $examType = request()->exam_type;

            switch ($examType) {
                case 'video':
                case 'subject_class':
                case 'lesson':
                    $full_exam = null;
                    $onlineExamType = ($examType == 'video') ? 'video' :
                        (($examType == 'subject_class') ?
                            'class' : 'lesson');
                    $onlineExam = OnlineExam::query()
                        ->where('id', $id)
                        ->where('type', $onlineExamType)
                        ->first();
                    break;

                case 'full_exam':
                    $full_exam = AllExam::query()
                        ->where('id', $id)
                        ->first();
                    $onlineExam = null;
                    break;

                default:
                    return self::returnResponseDataApi(null, "Invalid exam type", 422,422);
            }

            if (!$onlineExam && !$full_exam) {
                return self::returnResponseDataApi(null, "الامتحان غير موجود", 404);
            }

            $examDegreeDepends = ExamDegreeDepends::query()
            ->where('user_id', userId())
                ->where(function ($query) use ($id,$examType) {
                    $query->where(function ($q) use ($id,$examType) {
                        $column = ($examType == 'full_exam') ? 'all_exam_id' : 'online_exam_id';
                        $q->where($column, $id)
                            ->where('exam_depends', '=', 'yes');
                    });
                })
                ->first();

            if ($examDegreeDepends) {
                return self::returnResponseDataApi(null, "تم اعتماد هذا الامتحان من قبل", 201, 201);
            } else {
                $resource = ($examType == 'full_exam') ? new OnlineExamQuestionResource($full_exam) : new OnlineExamQuestionResource($onlineExam);

                return self::returnResponseDataApi($resource, "تم ارسال جميع الاسئله بالاجابات التابعه لهذا الامتحان", 200);
            }
        } catch (\Exception $exception) {

            return self::returnResponseDataApi(null, $exception->getMessage(), 500);
        }
    }

    public function online_exam_by_user(Request $request,$id): JsonResponse{

        try {

            $rules = [
                'exam_type' => 'required|in:video,subject_class,lesson,full_exam',
                'timer' => 'required|integer',
            ];
            $validator = Validator::make($request->all(), $rules, [
                'exam_type.in' => 407,
            ]);

            if ($validator->fails()) {
                $errors = collect($validator->errors())->flatten(1)[0];
                if (is_numeric($errors)) {

                    $errors_arr = [
                        407 => 'Failed,The exam type must be an video or lesson or subject_class or full_exam.',
                    ];

                    $code = collect($validator->errors())->flatten(1)[0];
                    return self::returnResponseDataApi(null, $errors_arr[$errors] ?? 500, $code);
                }
                return self::returnResponseDataApi(null, $validator->errors()->first(), 422);
            }


            $examType = request()->exam_type;
            $id = $request->id;

            if (in_array($examType, ['video', 'subject_class', 'lesson'])) {

                $examTypeCheck = $examType == 'subject_class' ? 'class' : $examType;

                $exam = OnlineExam::query()
                ->where('id', $id)
                    ->where('type', $examTypeCheck)
                    ->first();


                $examNotFoundMessage = $examType == 'subject_class' ? "امتحان الفصل غير موجود" : ($examType == 'video' ? 'امتحان الواجب غير موجود' : 'امتحان الدرس غير موجود');

                $count_trying = Timer::query()
                    ->where('online_exam_id', $exam->id)
                    ->where('user_id',userId())
                    ->count();

                $depends = ExamDegreeDepends::query()
                    ->where('online_exam_id', $exam->id)
                    ->where('user_id',userId())
                    ->where('exam_depends', 'yes')
                    ->first();


            } else {

                $exam = AllExam::query()
                ->where('id', $id)
                    ->first();

                $examNotFoundMessage = "الامتحان الشامل غير موجود";

                ################ تفقد عدد المحاولات لهذا الامتحان ##############
                $count_trying = Timer::query()
                    ->where('all_exam_id', '=', $exam->id)
                    ->where('user_id', '=',userId())
                    ->count();

                ################ تفقد في حاله هل تم اعتماد هذا الامتحان ام لا #########
                $depends = ExamDegreeDepends::query()
                    ->where('all_exam_id', '=', $exam->id)
                    ->where('user_id', '=',userId())
                    ->where('exam_depends', '=', 'yes')
                    ->first();
            }

            if (!$exam) {
                return self::returnResponseDataApi(null, $examNotFoundMessage, 404, 404);
            }



            $trying = $exam->trying_number;

            if (!$depends) {

                if ($count_trying < $trying) {

                    ###########################  تسجيل محاوله للطالب بجدول المحاولات والمحاوله يجب ان تكون اما تبع امتحان اونلاين من نوع (فصل,درس,فيديو شرح) او امتحان شامل#########
                    $timerData = [
                        'user_id' => userId(),
                        'timer' => request()->timer,
                    ];

                    if ($examType == 'full_exam') {
                        $timerData['all_exam_id'] = $exam->id;
                    } else {
                        $timerData['online_exam_id'] = $exam->id;
                    }

                    $timer = Timer::create($timerData);

                    foreach (request()->details as $detail) {

                        $question = Question::find($detail['question']);
                        $answer = Answer::find($detail['answer']);

                        ######### في حاله نوع السؤال اختياري #################################
                        if ($question->question_type == 'choice') {

                            $onlineExamUser = [
                                'timer_id' => $timer->id,
                                'user_id' => userId(),
                                'question_id' => $detail['question'],
                                'answer_id' => $detail['answer'],
                                'online_exam_id' => $examType == 'full_exam' ? null : $exam->id,
                                'all_exam_id' => $examType == 'full_exam' ? $exam->id : null,
                                'status' => $detail['answer'] == null ? "leave" : ($answer->answer_status == "correct" ? "solved" : "un_correct"),
                                'degree' => $detail['answer'] == null ? 0 : ($answer->answer_status == "correct" ? $question->degree : 0),
                            ];
                            OnlineExamUser::create($onlineExamUser);


                        } else {


                            ############### في حاله نوع السؤال مقالي والاجابه (نص او صوره او ملف صوتي) #######
                            $image = $detail['image'] ?? null;
                            $audio = $detail['audio'] ?? null;

                            if (isset($image) && $image != "") {
                                $destinationPath = 'text_user_exam_files/images/';
                                $file = date('YmdHis') . "." . $image->getClientOriginalExtension();
                                $image->move($destinationPath, $file);
                            }

                            if (isset($audio) && $audio != "") {
                                $audioPath = 'text_user_exam_files/audios/';
                                $fileAudio = date('YmdHis') . "." . $audio->getClientOriginalExtension();
                                $audio->move($audioPath, $fileAudio);
                            }

                            $textExamUser = [
                                'timer_id' => $timer->id,
                                'user_id' => userId(),
                                'question_id' => $detail['question'],
                                'online_exam_id' => $exam->id,
                                'answer' => $detail['answer'] ?? null,
                                'image' => $file ?? null,
                                'audio' => $fileAudio ?? null,
                                'answer_type' =>  $detail['answer'] ? 'text' : ($image ? 'file' : 'audio'),
                                'status' => (isset($detail['answer']) || isset($detail['image']) || isset($detail['audio'])) ? 'solved' : 'leave',
                            ];

                            TextExamUser::create($textExamUser);

                        }
                    }


                    ################ ارجاع بيانات الامتحان في حاله نوع الامتحان شامل ##################
                    if ($examType == 'full_exam') {

                        $sumDegree = OnlineExamUser::query()
                            ->where('user_id',userId())
                            ->where('all_exam_id', '=', $exam->id)
                            ->where('timer_id', '=', $timer->id)
                            ->sum('degree');


                        #################### تسجيل جميع المحاولات للطالب بجميع درجات كل محاوله لاعتماد اعلي درجه بهذا الامتحان ########
                        $examDegreeDependsData = [
                            'timer_id' => $timer->id,
                            'user_id' => userId(),
                            'all_exam_id' => $exam->id,
                            'full_degree' => $sumDegree,
                        ];

                        ExamDegreeDepends::create($examDegreeDependsData);

                        #################### تفصاصيل درجات الامتحان الشامل - عدد الاسئله الصحيحه - عدد الاسئله الخطاء - عدد الاسئله التي لم يتم حلها - اجمالي الوقت المستخدم - عدد المحاولات التي تمت لهذا الطالب لهذا الامتحان #######
                        $numOfCorrectQuestions = OnlineExamUser::query()
                            ->where('user_id',userId())
                            ->where('all_exam_id', '=', $exam->id)
                            ->where('timer_id', '=', $timer->id)
                            ->where('status', '=', 'solved')
                            ->count();

                        $numOfUnCorrectQtQuestions = OnlineExamUser::query()
                            ->where('user_id',userId())
                            ->where('all_exam_id', '=', $exam->id)
                            ->where('timer_id', '=', $timer->id)
                            ->where('status', '=', 'un_correct')
                            ->count();

                        $numOfLeaveQuestions = OnlineExamUser::query()
                            ->where('user_id',userId())
                            ->where('all_exam_id', '=', $exam->id)
                            ->where('timer_id', '=', $timer->id)
                            ->where('status', '=', 'leave')
                            ->count();

                        $totalTime = Timer::query()
                            ->where('user_id',userId())
                            ->where('all_exam_id', '=', $exam->id)
                            ->latest()
                            ->first();

                        $total_trying = Timer::query()
                            ->where('all_exam_id', '=', $exam->id)
                            ->where('user_id', '=',userId())
                            ->count();

                        $data['degree'] = $sumDegree . "/" . $exam->degree;
                        $data['ordered'] = 1;
                        $data['exam_id'] = $exam->id;
                        $data['exam_name'] = in_array(request()->exam_type, ['video', 'subject_class', 'lesson']) ? 'online_exam' : 'full_exam';
                        $data['exam_type'] = request()->exam_type;
                        $data['trying_number'] = $total_trying;
                        $data['motivational_word'] = "ممتاز بس فيه أحسن ";
                        $data['num_of_correct_questions'] = $numOfCorrectQuestions;
                        $data['num_of_mistake_questions'] = $numOfUnCorrectQtQuestions;
                        $data['num_of_leave_questions'] = $numOfLeaveQuestions;
                        $data['total_time_take'] = $totalTime->timer;
                        $data['total_time_exam'] = $exam->quize_minute;
                        $data['title_result'] = $exam->title_result;
                        $data['description_result'] = $exam->description_result;
                        $data['image_result'] = $exam->image_result == null ? asset('all_exam_result_images/default/default.png') :
                            asset('all_exam_result_images/images/' . $exam->image_result);


                    } else {


                        $sumDegree = OnlineExamUser::query()
                            ->where('user_id',userId())
                            ->where('timer_id', '=', $timer->id)
                            ->where('online_exam_id', '=', $exam->id)
                            ->sum('degree');

                        $examDegreeDependsData = [
                            'timer_id' => $timer->id,
                            'user_id' => userId(),
                            'online_exam_id' => $exam->id,
                            'full_degree' => $sumDegree,
                        ];

                        ExamDegreeDepends::create($examDegreeDependsData);


                        ###############تفصاصيل درجات الامتحان الاونلاين (فيديو-درس-فصل) - عدد الاسئله الصحيحه - عدد الاسئله الخطاء - عدد الاسئله التي لم يتم حلها - اجمالي الوقت المستخدم - عدد المحاولات التي تمت لهذا الطالب لهذا الامتحان ###########
                        $numOfCorrectQuestions = OnlineExamUser::query()
                            ->where('user_id', userId())
                            ->where('timer_id', '=', $timer->id)
                            ->where('online_exam_id', '=', $exam->id)
                            ->where('status', '=', 'solved')->count();

                        $numOfUnCorrectQtQuestions = OnlineExamUser::query()
                            ->where('user_id', userId())
                            ->where('timer_id', '=', $timer->id)
                            ->where('online_exam_id', '=', $exam->id)
                            ->where('status', '=', 'un_correct')->count();


                        $numOfLeaveQuestions = OnlineExamUser::query()
                            ->where('user_id', userId())
                            ->where('online_exam_id', '=', $exam->id)
                            ->where('timer_id', '=', $timer->id)
                            ->where('status', '=', 'leave')->count();

                        $totalTime = Timer::query()
                            ->where('user_id', userId())
                            ->where('online_exam_id', '=', $exam->id)
                            ->latest()
                            ->first();

                        $total_trying = Timer::query()
                            ->where('online_exam_id', '=', $exam->id)
                            ->where('user_id', '=',userId())
                            ->count();

                        $data['degree'] = $sumDegree . "/" . $exam->degree;
                        $data['ordered'] = 1;
                        $data['exam_id'] = $exam->id;
                        $data['exam_name'] = in_array(request()->exam_type, ['video', 'subject_class', 'lesson']) ? 'online_exam' : 'full_exam';
                        $data['exam_type'] = request()->exam_type;
                        $data['trying_number'] = $total_trying;
                        $data['motivational_word'] = "ممتاز بس فيه أحسن ";
                        $data['num_of_correct_questions'] = $numOfCorrectQuestions;
                        $data['num_of_mistake_questions'] = $numOfUnCorrectQtQuestions;
                        $data['num_of_leave_questions'] = $numOfLeaveQuestions;
                        $data['total_time_take'] = (int)$totalTime->timer;
                        $data['total_time_exam'] = $exam->quize_minute;
                        $data['title_result'] = $exam->title_result;
                        $data['description_result'] = $exam->description_result;
                        $data['image_result'] = $exam->image_result == null ? asset('online_exam_result_images/default/default.png') : asset('online_exam_result_images/images/' . $exam->image_result);

                    }

                    return self::returnResponseDataApiWithMultipleIndexes($data, "تم اداء الامتحان بنجاح وارسال تفاصيل نتيجه الطالب", 200);

                } else {
                    return self::returnResponseDataApi(null, "لقد انتهيت من جميع محاولاتك لهذا الامتحان ولا يوجد لديك محاولات اخري", 415);
                }



            } else {
                return self::returnResponseDataApi(null, "تم اعتماد الدرجه لهذا الامتحان من قبل", 416);
            }

        } catch (\Exception $exception) {

            return self::returnResponseDataApi(null, $exception->getMessage(), 500);
        }

    }


    public function access_end_time_for_exam(Request $request, $id)
    {

        $rules = [
            'type' => 'required|in:video,subject_class,lesson,full_exam',
        ];
        $validator = Validator::make($request->all(), $rules, [
            'type.in' => 407,
        ]);

        if ($validator->fails()) {
            $errors = collect($validator->errors())->flatten(1)[0];
            if (is_numeric($errors)) {

                $errors_arr = [
                    407 => 'Failed,The exam type must be an video or lesson or subject_class or full_exam.',
                ];

                $code = collect($validator->errors())->flatten(1)[0];
                return self::returnResponseDataApi(null, $errors_arr[$errors] ?? 500, $code);
            }
            return self::returnResponseDataApi(null, $validator->errors()->first(), 422);
        }

        if (in_array($request->type,['video','subject_class','lesson'])) {

            $exam = OnlineExam::query()
            ->where('id', $id)
                ->where('type', '=', $request->type)
                ->first();
            if (!$exam) {
                return self::returnResponseDataApi(null, "الامتحان غير موجود", 404);
            }

            Timer::create([
                'online_exam_id' => $exam->id,
                'user_id' => Auth::guard('user-api')->id(),
                'timer' => $request->timer,

            ]);
            return self::returnResponseDataApi(null, "تم اضافه محاوله جديده", 200);

        } else {
            if ($request->type == 'full_exam') {

                $exam = AllExam::query()
                ->where('id', $id)->first();

                if (!$exam) {
                    return self::returnResponseDataApi(null, "الامتحان ال موجود", 404);
                }

                Timer::create([
                    'all_exam_id' => $exam->id,
                    'user_id' => userId(),
                    'timer' => $request->timer,
                ]);
                return self::returnResponseDataApi(null, "تم اضافه محاوله جديده للامتحان الشامل", 200);

            }
        }

    }

    public function degreesDependsWithStudent(Request $request,$id){

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
                    return self::returnResponseDataApi(null, $errors_arr[$errors] ?? 500, $code);
                }
                return self::returnResponseDataApi(null, $validator->errors()->first(), 422);
            }

        $examType = request()->exam_type;

        if (in_array($examType, ['video', 'subject_class', 'lesson'])) {
        $exam = OnlineExam::find($id);

        if (!$exam) {
        return self::returnResponseDataApi(null, "هذا الامتحان غير موجود", 404, 404);
        }

        $examDegreeDepends = ExamDegreeDepends::query()
            ->where('online_exam_id', $exam->id)
        ->where('user_id', userId())
        ->orderBy('full_degree', 'DESC')
        ->first();

        if (!$examDegreeDepends) {
        return self::returnResponseDataApi(null, "يرجى إدخال هذا الامتحان أولاً لاعتماد أعلى درجة", 404, 404);
        }

        $examDegreeDepends->update(['exam_depends' => 'yes']);
        return self::returnResponseDataApi(null, "تم اعتماد درجة الاونلاين بنجاح", 200);

        } else {

        if ($examType == 'full_exam') {

        $exam = AllExam::find($id);

        if (!$exam) {
        return self::returnResponseDataApi(null, "الامتحان الشامل غير موجود", 404, 404);
        }

        $examDegreeDepends = ExamDegreeDepends::query()
            ->where('all_exam_id', $exam->id)
        ->where('user_id',userId())
        ->orderBy('full_degree', 'DESC')
        ->first();

        if (!$examDegreeDepends) {
        return self::returnResponseDataApi(null, "يرجى إدخال الامتحان الشامل أولاً لاعتماد أعلى درجة", 404, 404);
    }

        $examDegreeDepends->update(['exam_depends' => 'yes']);
        return self::returnResponseDataApi(null, "تم اعتماد درجة الامتحان الشامل بنجاح", 200);

        }
    }

        }catch (\Exception $exception) {
            return self::returnResponseDataApi(null, $exception->getMessage(), 500);
        }
    }



    final public function examHeroesAll(): JsonResponse
    {

        try {

            $userCountExam = ExamDegreeDepends::query()
                ->where('user_id','=',userId())
                ->where('exam_depends','=', 'yes')
                ->count();

            if($userCountExam > 0) {

                $response = [
                'day' => $this->day(),
                'week' => $this->week(),
                'current_month' => $this->currentMonth(),
                'last_month' => $this->lastMonth(),
                'day_heroes' => $this->dayHeroesAll(),
                'week_heroes' => $this->weekHeroesAll(),
                'current_month_heroes' => $this->currentMonthHeroesAll(),
                'last_month_heroes' => $this->lastMonthHeroesAll(),
                ];

                return self::returnResponseDataApi($response, "تم الحصول علي ابطال الامتحانات  بنجاح", 200);

            }else{

                return self::returnResponseDataApi(null, "يجب دخول امتحان واحد علي الاقل لاظهار ابطال المنصه", 403);
            }

        } catch (\Exception $exception) {

            return self::returnResponseDataApi(null, $exception->getMessage(), 500);
        }

     }


    public function day(): ?StudentHeroResource
    {

        DB::select(DB::raw('SET @rank = 0'));
        $result = DB::table('exam_degree_depends')
            ->where('user_id','=',userId())
            ->where('exam_depends','=','yes')
            ->leftJoin('online_exams','online_exams.id','=','exam_degree_depends.online_exam_id')
            ->leftJoin('all_exams','all_exams.id','=','exam_degree_depends.all_exam_id')
            ->join('users','users.id','=','exam_degree_depends.user_id')
            ->join('countries','countries.id','=','users.country_id')
            ->select(
                'users.id',
                'users.name as name',
                'users.image as image',
                'countries.name_ar as country',
                DB::raw('SUM(exam_degree_depends.full_degree) as student_total_degrees'),
                DB::raw('SUM(online_exams.degree) as degree_online_exam'),
                DB::raw('SUM(all_exams.degree) as degree_all_exam'),
                DB::raw('@rank := @rank + 1 as ordered')
            )
            ->whereDay('exam_degree_depends.created_at', '=', Carbon::now()->format('d'))
            ->groupBy('user_id')
            ->first();

        if(!empty($result)){
            return new StudentHeroResource($result);
        }else{
            return null;
        }

    }


    public function week(): ?StudentHeroResource
    {

        DB::select(DB::raw('SET @rank = 0'));
        $result = DB::table('exam_degree_depends')
            ->where('user_id','=',userId())
            ->where('exam_depends','=','yes')
            ->leftJoin('online_exams','online_exams.id','=','exam_degree_depends.online_exam_id')
            ->leftJoin('all_exams','all_exams.id','=','exam_degree_depends.all_exam_id')
            ->join('users','users.id','=','exam_degree_depends.user_id')
            ->join('countries','countries.id','=','users.country_id')
            ->select(
                'users.id',
                'users.name as name',
                'users.image as image',
                'countries.name_ar as country',
                DB::raw('SUM(exam_degree_depends.full_degree) as student_total_degrees'),
                DB::raw('SUM(online_exams.degree) as degree_online_exam'),
                DB::raw('SUM(all_exams.degree) as degree_all_exam'),
                DB::raw('@rank := @rank + 1 as ordered')
            )
            ->whereBetween('exam_degree_depends.created_at', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])
            ->groupBy('user_id')
            ->first();

        if(!empty($result)){
            return new StudentHeroResource($result);
        }else{
            return null;
        }

    }

    public function currentMonth(): ?StudentHeroResource
    {

        DB::select(DB::raw('SET @rank = 0'));
        $result = DB::table('exam_degree_depends')
            ->where('user_id','=',userId())
            ->where('exam_depends','=','yes')
            ->leftJoin('online_exams','online_exams.id','=','exam_degree_depends.online_exam_id')
            ->leftJoin('all_exams','all_exams.id','=','exam_degree_depends.all_exam_id')
            ->join('users','users.id','=','exam_degree_depends.user_id')
            ->join('countries','countries.id','=','users.country_id')
            ->select(
                'users.id',
                'users.name as name',
                'users.image as image',
                'countries.name_ar as country',
                DB::raw('SUM(exam_degree_depends.full_degree) as student_total_degrees'),
                DB::raw('SUM(online_exams.degree) as degree_online_exam'),
                DB::raw('SUM(all_exams.degree) as degree_all_exam'),
                DB::raw('@rank := @rank + 1 as ordered')
            )
            ->whereMonth('exam_degree_depends.created_at', Carbon::now()->format('m'))
            ->groupBy('user_id')
            ->first();

        if(!empty($result)){
            return new StudentHeroResource($result);
        }else{
            return null;
        }

    }


    public function lastMonth(): ?StudentHeroResource
    {

        DB::select(DB::raw('SET @rank = 0'));
        $result = DB::table('exam_degree_depends')
            ->where('user_id','=',userId())
            ->where('exam_depends','=','yes')
            ->leftJoin('online_exams','online_exams.id','=','exam_degree_depends.online_exam_id')
            ->leftJoin('all_exams','all_exams.id','=','exam_degree_depends.all_exam_id')
            ->join('users','users.id','=','exam_degree_depends.user_id')
            ->join('countries','countries.id','=','users.country_id')
            ->select(
                'users.id',
                'users.name as name',
                'users.image as image',
                'countries.name_ar as country',
                DB::raw('SUM(exam_degree_depends.full_degree) as student_total_degrees'),
                DB::raw('SUM(online_exams.degree) as degree_online_exam'),
                DB::raw('SUM(all_exams.degree) as degree_all_exam'),
                DB::raw('@rank := @rank + 1 as ordered')
            )
            ->whereMonth('exam_degree_depends.created_at', Carbon::now()->subMonth()->format('m'))
            ->groupBy('user_id')
            ->first();

        if(!empty($result)){
            return new StudentHeroResource($result);
        }else{
            return null;
        }

    }



    public function dayHeroesAll()
    {

        DB::select(DB::raw('SET @rank = 0'));
        $result = DB::table('exam_degree_depends')
            ->where('exam_depends','=','yes')
            ->leftJoin('online_exams','online_exams.id','=','exam_degree_depends.online_exam_id')
            ->leftJoin('all_exams','all_exams.id','=','exam_degree_depends.all_exam_id')
            ->join('users','users.id','=','exam_degree_depends.user_id')
            ->join('countries','countries.id','=','users.country_id')
            ->select(
                'users.id',
                'users.name as name',
                'users.image as image',
                'countries.name_ar as country',
                DB::raw('SUM(exam_degree_depends.full_degree) as student_total_degrees'),
                DB::raw('SUM(online_exams.degree) as degree_online_exam'),
                DB::raw('SUM(all_exams.degree) as degree_all_exam'),
                DB::raw('@rank := @rank + 1 as ordered')
            )
            ->whereDay('exam_degree_depends.created_at', '=', Carbon::now()->format('d'))
            ->groupBy('user_id')
            ->orderByDesc('student_total_degrees')
            ->get();

        if(!empty($result)){
            return StudentHeroResource::collection($result);
        }else{
            return [];
        }



    }



   public function weekHeroesAll()
   {

       DB::select(DB::raw('SET @rank = 0'));
       $result = DB::table('exam_degree_depends')
           ->where('exam_depends','=','yes')
           ->leftJoin('online_exams','online_exams.id','=','exam_degree_depends.online_exam_id')
           ->leftJoin('all_exams','all_exams.id','=','exam_degree_depends.all_exam_id')
           ->join('users','users.id','=','exam_degree_depends.user_id')
           ->join('countries','countries.id','=','users.country_id')
           ->select(
               'users.id',
               'users.name as name',
               'users.image as image',
               'countries.name_ar as country',
               DB::raw('SUM(exam_degree_depends.full_degree) as student_total_degrees'),
               DB::raw('SUM(online_exams.degree) as degree_online_exam'),
               DB::raw('SUM(all_exams.degree) as degree_all_exam'),
               DB::raw('@rank := @rank + 1 as ordered')
           )
           ->whereBetween('exam_degree_depends.created_at', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])
           ->groupBy('user_id')
           ->orderByDesc('student_total_degrees')
           ->get();

       if(!empty($result)){
           return StudentHeroResource::collection($result);
       }else{
           return [];
       }


    }


    public function currentMonthHeroesAll()
    {

        DB::select(DB::raw('SET @rank = 0'));
        $result = DB::table('exam_degree_depends')
            ->where('exam_depends','=','yes')
            ->leftJoin('online_exams','online_exams.id','=','exam_degree_depends.online_exam_id')
            ->leftJoin('all_exams','all_exams.id','=','exam_degree_depends.all_exam_id')
            ->join('users','users.id','=','exam_degree_depends.user_id')
            ->join('countries','countries.id','=','users.country_id')
            ->select(
                'users.id',
                'users.name as name',
                'users.image as image',
                'countries.name_ar as country',
                DB::raw('SUM(exam_degree_depends.full_degree) as student_total_degrees'),
                DB::raw('SUM(online_exams.degree) as degree_online_exam'),
                DB::raw('SUM(all_exams.degree) as degree_all_exam'),
                DB::raw('@rank := @rank + 1 as ordered')
            )
            ->whereMonth('exam_degree_depends.created_at', Carbon::now()->format('m'))
            ->groupBy('user_id')
            ->orderByDesc('student_total_degrees')
            ->get();

        if(!empty($result)){
            return StudentHeroResource::collection($result);
        }else{
            return [];
        }

    }


    public function lastMonthHeroesAll()
    {

        DB::select(DB::raw('SET @rank = 0'));
        $result = DB::table('exam_degree_depends')
            ->where('exam_depends','=','yes')
            ->leftJoin('online_exams','online_exams.id','=','exam_degree_depends.online_exam_id')
            ->leftJoin('all_exams','all_exams.id','=','exam_degree_depends.all_exam_id')
            ->join('users','users.id','=','exam_degree_depends.user_id')
            ->join('countries','countries.id','=','users.country_id')
            ->select(
                'users.id',
                'users.name as name',
                'users.image as image',
                'countries.name_ar as country',
                DB::raw('SUM(exam_degree_depends.full_degree) as student_total_degrees'),
                DB::raw('SUM(online_exams.degree) as degree_online_exam'),
                DB::raw('SUM(all_exams.degree) as degree_all_exam'),
                DB::raw('@rank := @rank + 1 as ordered')
            )
            ->whereMonth('exam_degree_depends.created_at', Carbon::now()->subMonth()->format('m'))
            ->groupBy('user_id')
            ->orderByDesc('student_total_degrees')
            ->get();

        if(!empty($result)){
            return StudentHeroResource::collection($result);
        }else{
            return [];
        }

    }


}
