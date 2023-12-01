<?php

namespace App\Http\Repositories;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Slider;
use App\Models\AllExam;
use App\Models\Section;
use App\Models\Setting;
use App\Models\LifeExam;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\OpenLesson;
use App\Models\PhoneToken;
use App\Models\Suggestion;
use App\Models\VideoBasic;
use App\Models\VideoParts;
use App\Models\ExamSchedule;
use App\Models\Notification;
use App\Models\SubjectClass;
use Illuminate\Http\Request;
use App\Models\VideoResource;
use App\Models\PapelSheetExam;
use App\Models\UserScreenShot;
use App\Models\ExamDegreeDepends;
use Illuminate\Http\JsonResponse;
use App\Models\PapelSheetExamTime;
use App\Models\PapelSheetExamUser;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\HomeAllClasses;
use App\Http\Resources\SliderResource;
use App\Http\Resources\AllExamResource;
use App\Http\Resources\SuggestResource;
use App\Models\NotificationSeenStudent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PapelSheetResource;
use App\Http\Resources\PhoneTokenResource;
use App\Http\Resources\VideoBasicResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\CommunicationResource;
use App\Http\Resources\OnlineExamNewResource;
use App\Http\Resources\VideoResourceResource;
use App\Http\Resources\SubjectClassNewResource;
use App\Http\Interfaces\AuthRepositoryInterface;
use App\Http\Resources\PapelSheetExamTimeUserResource;
use App\Http\Controllers\Api\Traits\FirebaseNotification;
use Illuminate\Http\Response;
use function Clue\StreamFilter\fun;


class AuthRepository extends ResponseApi implements AuthRepositoryInterface
{


    use FirebaseNotification;

    public function login(Request $request): JsonResponse
    {
        try {
            $rules = [
                'code' => 'required|exists:users,code',
            ];
            $validator = Validator::make($request->all(), $rules, [
                'code.exists' => 407,
            ]);

            if ($validator->fails()) {
                $errors = collect($validator->errors())->flatten(1)[0];
                if (is_numeric($errors)) {

                    $errors_arr = [
                        407 => 'Failed,Code not found.',
                    ];

                    $code = collect($validator->errors())->flatten(1)[0];
                    return self::returnResponseDataApi(null, $errors_arr[$errors] ?? 500, $code);
                }
                return self::returnResponseDataApi(null, $validator->errors()->first(), 422);
            }

            $token = Auth::guard('user-api')->attempt(['code' => $request->code, 'password' => '123456', 'user_status' => 'active']);

            $user_data = User::query()
            ->where('code', '=', $request->code)
                ->first();


            if ($user_data->login_status == 1) {
                return self::returnResponseDataApi(null, "هذا الطالب مسجل دخول من جهاز اخر!", 403);

            }
            if (!$token) {
                return self::returnResponseDataApi(null, "الطالب غير مفعل برجاء التواصل مع السيكرتاريه", 408);
            }
            $user = Auth::guard('user-api')->user();
            $user['token'] = $token;

            $user_data->update(['access_token' => $token]);

            $user_data->update(['login_status' => 1]);
            if($user_data->code != 40907030) {
                $user_data->update(['login_status' => 1]);

            }

            return self::returnResponseDataApi(new UserResource($user), "تم تسجيل الدخول بنجاح", 200);
        } catch (\Exception $exception) {

            return self::returnResponseDataApi(null, $exception->getMessage(), 500);
        }
    }

    public function addSuggest(Request $request): JsonResponse
    {

        try {
            $rules = [
                'suggestion' => 'nullable|string',
                'type' => 'required|in:file,text,audio',
                'audio' => 'nullable',
                'image' => 'nullable|mimes:jpg,png,jpeg'
            ];
            $validator = Validator::make($request->all(), $rules, [
                'suggestion.string' => 407,
                'image.mimes' => 408,
                'type.in' => 409
            ]);

            if ($validator->fails()) {

                $errors = collect($validator->errors())->flatten(1)[0];
                if (is_numeric($errors)) {

                    $errors_arr = [
                        407 => 'Failed,Suggestion must be an a string.',
                        408 => 'Failed,The image type must be an jpg or jpeg or png.',
                        409 => 'Failed,The type of suggestion must be an file or text or audio',
                    ];

                    $code = collect($validator->errors())->flatten(1)[0];
                    return self::returnResponseDataApi(null, isset($errors_arr[$errors]) ? $errors_arr[$errors] : 500, $code);
                }
                return self::returnResponseDataApi(null, $validator->errors()->first(), 422);
            }

            if ($image = $request->file('image')) {

                $destinationPath = 'suggestions_uploads/images/';
                $file = date('YmdHis') . "." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $file);
                $request['image'] = "$file";

            } elseif ($audio = $request->file('audio')) {

                $audioPath = 'suggestions_uploads/audios/';
                $audioUpload = date('YmdHis') . "." . $audio->getClientOriginalExtension();
                $audio->move($audioPath, $audioUpload);
                $request['audio'] = "$audioUpload";

            } else {

                $suggestion = $request->suggestion;
            }

            if ($request->suggestion == null && $request->audio == null && $request->image == null) {

                return self::returnResponseDataApi(null, "يجب كتابه اقتراح او ارفاق صوره او رفع ملف صوتي", 422);
            }
            $suggestion_add = Suggestion::create([
                'user_id' => Auth::guard('user-api')->id(),
                'audio' => $audioUpload ?? null,
                'image' => $file ?? null,
                'type' => $request->type,
                'suggestion' => $suggestion ?? null,
            ]);

            if (isset($suggestion_add)) {
                $suggestion_add->user->token = $request->bearerToken();
                return self::returnResponseDataApi(new SuggestResource($suggestion_add), "تم تسجيل الاقتراح بنجاح", 200);
            } else {
                return self::returnResponseDataApi(null, "يوجد خطاء ما اثناء دخول البيانات", 500);
            }
        } catch (\Exception $exception) {

            return self::returnResponseDataApi(null, $exception->getMessage(), 500);
        }
    }


    public function allNotifications(): JsonResponse
    {

        try {


            $userId =userId();

            $allNotification = Notification::query()
                ->whereDate('created_at','=',date('Y-m-d'))
                ->where(function ($q) use ($userId) {
                    $q->where('season_id', '=',getSeasonIdOfStudent())
                        ->orWhereJsonContains('group_ids', "$userId")
                        ->orWhere('user_id', '=', userId());
                })
                ->latest()
                ->get();


            return self::returnResponseDataApi(NotificationResource::collection($allNotification), "تم ارسال اشعارات المستخدم بنجاح", 200);
        } catch (\Exception $exception) {

            return self::returnResponseDataApi(null, $exception->getMessage(), 500);
        }
    }

    public function communication(): JsonResponse
    {
        try {
            $setting = Setting::query()
                ->latest()
                ->first();

            return self::returnResponseDataApi(new CommunicationResource($setting), "تم الحصول علي بيانات التواصل مع السكيرتاريه", 200);
        } catch (\Exception $exception) {

            return self::returnResponseDataApi(null, $exception->getMessage(), 500);
        }
    }

    public function getProfile(Request $request): JsonResponse
    {
        try {
            $user = Auth::guard('user-api')->user();
            $user['token'] = $request->bearerToken();

            return self::returnResponseDataApi(new UserResource($user), "تم الحصول على بيانات الطالب بنجاح", 200);
        } catch (\Exception $exception) {
            return self::returnResponseDataApi(null, $exception->getMessage(), 500);
        }
    }


    public function paper_sheet_exam(Request $request,$id)
    {

        $rules = [
            'papel_sheet_exam_time_id' => 'required|exists:papel_sheet_exam_times,id',
        ];
        $validator = Validator::make($request->all(), $rules, [
            'papel_sheet_exam_time_id.exists' => 405,
        ]);

        if ($validator->fails()) {

            $errors = collect($validator->errors())->flatten(1)[0];
            if (is_numeric($errors)) {

                $errors_arr = [
                    405 => 'Failed,paper_sheet_exam_time_id does not exist',
                ];

                $code = collect($validator->errors())->flatten(1)[0];
                return self::returnResponseDataApi(null, $errors_arr[$errors] ?? 500, $code);
            }
            return self::returnResponseDataApi(null, $validator->errors()->first(), 422);
        }

        $paperSheetExam = PapelSheetExam::query()
            ->whereHas('season', fn (Builder $builder) =>
            $builder->where('season_id', '=',getSeasonIdOfStudent()))
            ->whereHas('term', fn (Builder $builder) =>
            $builder->where('status', '=', 'active')
                ->where('season_id', '=', getSeasonIdOfStudent()))
            ->where('id', '=', $id)
            ->first();


        if (!$paperSheetExam) {
            return self::returnResponseDataApi(null, "لا يوجد اي امتحان ورقي متاح لك", 404);
        }

        $ids = Section::query()
            ->orderBy('id', 'ASC')
            ->pluck('id')
            ->toArray();

        foreach ($ids as $id) {

            $sectionCheck = Section::query()
                ->where('id', '=', $id)
                ->first();

            $CheckCountSectionExam = PapelSheetExamUser::query()
                ->where('section_id', '=', $sectionCheck->id)
                ->where('papel_sheet_exam_id', '=', $paperSheetExam->id)
                ->count();

            $userRegisterExamBefore = PapelSheetExamUser::query()
                ->where('papel_sheet_exam_id', '=', $paperSheetExam->id)
                ->where('user_id', '=',userId())
                ->count();

            $sumCapacityOfSection = Section::sum('capacity');

            $countExamId = PapelSheetExamUser::query()
                ->where('papel_sheet_exam_time_id','=',$request->papel_sheet_exam_time_id)
                ->where('papel_sheet_exam_id', '=', $paperSheetExam->id)
                ->count();

            if ($countExamId < $sumCapacityOfSection) {

                if ($CheckCountSectionExam < $sectionCheck->capacity) {

                    if ($CheckCountSectionExam == $sectionCheck->capacity) {

                        $section = Section::query()
                            ->orderBy('id', 'ASC')
                            ->get()->except($sectionCheck->id)
                            ->where('id', '>', $sectionCheck->id);
                    } else {
                        $section = Section::query()
                            ->where('id', '=', $id)->first();
                    }

                    if (Auth::guard('user-api')->user()->center == 'out') {
                        return self::returnResponseDataApi(null, "لا يمكنك التسجيل في الامتحان الورقي لانك خارج السنتر", 407);
                    } else {

                        if ($userRegisterExamBefore > 0) {

                            $paperSheetExamUserRegisterWithStudentBefore = PapelSheetExamUser::query()
                                ->where('user_id', '=',userId())
                                ->where('papel_sheet_exam_id', '=', $paperSheetExam->id)
                                ->first();

                            $timeOfPaperSheetExamUser = PapelSheetExamTime::query()
                                ->where('id', '=', $request->papel_sheet_exam_time_id)
                                ->first();


                            $data['nameOfExam'] = lang() == 'ar' ? $paperSheetExam->name_ar : $paperSheetExam->name_en;
                            $data['dateExam'] = $paperSheetExam->date_exam;
                            $data['time'] = $timeOfPaperSheetExamUser->from;
                            $data['address'] = $paperSheetExamUserRegisterWithStudentBefore->sections->address;
                            $data['section'] = lang() == 'ar' ? $paperSheetExamUserRegisterWithStudentBefore->sections->section_name_ar : $paperSheetExamUserRegisterWithStudentBefore->sections->section_name_en;

                            return self::returnResponseDataApiWithMultipleIndexes($data, "تم تسجيل بياناتك في الامتحان الورقي من قبل", 201);
                        } else {

                            if (Carbon::now()->format('Y-m-d') <= $paperSheetExam->to) {
                                $createPaperSheet = new PapelSheetExamUser();
                                $createPaperSheet->user_id = Auth::guard('user-api')->id();
                                $createPaperSheet->section_id = $section->id;
                                $createPaperSheet->papel_sheet_exam_id = $paperSheetExam->id;
                                $createPaperSheet->papel_sheet_exam_time_id = $request->papel_sheet_exam_time_id;
                                $createPaperSheet->save();

                                if ($createPaperSheet->save()) {
                                    $time_exam = PapelSheetExamTime::query()->where('id', '=', $request->papel_sheet_exam_time_id)->first();
                                    $this->sendFirebaseNotification(['title' => 'اشعار جديد', 'body' => $time_exam->from . 'وموعد الامتحان  ' . $section->section_name_ar . 'واسم القاعه  ' . $section->address . 'ومكان الامتحان  ' . $paperSheetExam->date_exam . 'تاريخ الامتحان'], $paperSheetExam->season_id, Auth::guard('user-api')->id());

                                    $data['nameOfExam'] = lang() == 'ar' ? $paperSheetExam->name_ar : $paperSheetExam->name_en;
                                    $data['dateExam'] = $paperSheetExam->date_exam;
                                    $data['time'] = $time_exam->from;
                                    $data['address'] = $section->address;
                                    $data['section'] = lang() == 'ar' ? $section->section_name_ar : $section->section_name_en;

                                    return self::returnResponseDataApiWithMultipleIndexes($data, "تم تسجيل بياناتك في الامتحان", 200);
                                } else {
                                    return self::returnResponseDataApi(null, "يوجد خطاء ما اثناء تسجيل بيانات الامتحان الورقي", 500);
                                }
                            } else {
                                return self::returnResponseDataApi(null, "!لقد تعديت اخر موعد لتسجيل الامتحان", 412);
                            }
                        }
                    }
                }
            } else {
                return self::returnResponseDataApi(null, "تم امتلاء القاعات لهذا الامتحان برجاء التواصل مع السيكرتاريه", 411);
            }
        }
    }


    public function latestPaperExamDelete(): JsonResponse
    {
        try {

            $paperSheetExamUser = PapelSheetExamUser::query()->latest()
                ->where('user_id', '=', Auth::guard('user-api')->id())
                ->first();


            if ($paperSheetExamUser) {

                if(Carbon::parse($paperSheetExamUser->papelSheetExam->to)->subDays(2)->format('Y-m-d') == Carbon::now()->format('Y-m-d') || Carbon::parse($paperSheetExamUser->papelSheetExam->to)->subDay()->format('Y-m-d') == Carbon::now()->format('Y-m-d') || Carbon::parse($paperSheetExamUser->papelSheetExam->to)->format('Y-m-d') == Carbon::now()->format('Y-m-d')){

                    return self::returnResponseDataApi(null, "غير مسموح لك بحذف الامتحان الورقي", 403);

                }else{

                    $paperSheetExamUser->delete();
                    return self::returnResponseDataApi(null, "تم الغاء الامتحان الورقي بنجاح", 200);
                }


            } else {

                return self::returnResponseDataApi(null, "لا يوجد اي امتحان ورقي لك لالغائه", 404);
            }
        } catch (\Exception $exception) {

            return self::returnResponseDataApi(null, $exception->getMessage(), 500);
        }
    }


    public function paperSheetExamForStudentDetails(): JsonResponse
    {

        $userRegisterExamBefore = PapelSheetExamUser::query()
            ->where('user_id', '=',userId())
            ->latest()
            ->first();

        /*
         اذا كان يوجد امتحان ورقي متاح للطالب
        */
        $paperSheetCheckExam = PapelSheetExam::query()
        ->whereHas('season', fn (Builder $builder) =>
        $builder->where('season_id', '=',getSeasonIdOfStudent()))
            ->whereHas('term', fn (Builder $builder) =>
            $builder->where('status', '=', 'active')
                ->where('season_id', '=',getSeasonIdOfStudent()))
            ->whereDate('to', '>=', Carbon::now()->format('Y-m-d'))
            ->first();


        if (!$paperSheetCheckExam) {

            return self::returnResponseDataApi(null, "لا يوجد امتحان ورقي", 404);
        } else {

            if ($userRegisterExamBefore) {

                $paperSheetExam = PapelSheetExam::query()
                ->whereHas('season', fn (Builder $builder) =>
                $builder->where('season_id', '=',getSeasonIdOfStudent()))
                    ->whereHas('term', fn (Builder $builder) =>
                    $builder->where('status', '=', 'active')
                        ->where('season_id', '=',getSeasonIdOfStudent()))
                    ->where('id', '=', $userRegisterExamBefore->papel_sheet_exam_id)
                    ->first();


                $data['nameOfExam'] = lang() == 'ar' ? $paperSheetExam->name_ar : $paperSheetExam->name_en;
                $data['dateExam'] = $userRegisterExamBefore->papelSheetExam->date_exam;
                $data['time'] = $userRegisterExamBefore->papelSheetExamTime->from;
                $data['address'] = $userRegisterExamBefore->sections->address;
                $data['section'] = lang() == 'ar' ? $userRegisterExamBefore->sections->section_name_ar : $userRegisterExamBefore->sections->section_name_en;

                return self::returnResponseDataApi($data, "تم التسجيل في الامتحان الورقي من قبل", 201);
            } else {
                return self::returnResponseDataApi(new PapelSheetResource($paperSheetCheckExam), "يجب الرجوع لصفحه تسجيل الامتحان الورقي", 200);
            }
        }
    }

    public function paper_sheet_exam_show(): JsonResponse
    {

        $paperSheetExam = PapelSheetExam::query()
        ->whereHas('season', fn (Builder $builder) =>
        $builder->where('season_id', '=',getSeasonIdOfStudent()))
            ->whereHas('term', fn (Builder $builder) =>
            $builder->where('status', '=', 'active')
                ->where('season_id', '=', getSeasonIdOfStudent()))
            ->whereDate('to', '>=', Carbon::now()->format('Y-m-d'))
            ->first();

        if (!$paperSheetExam) {
            return self::returnResponseDataApi(null, "لا يوجد امتحان ورقي", 404);
        }

        return self::returnResponseDataApi(new PapelSheetResource($paperSheetExam), "اهلا بك في الامتحان الورقي", 200);
    }


    public function updateProfile(Request $request): JsonResponse
    {

        $rules = [
            'image' => 'nullable|image|mimes:jpg,png,jpeg',
        ];
        $validator = Validator::make($request->all(), $rules, [
            'image.mimes' => 407,
            'images.image' => 408
        ]);

        if ($validator->fails()) {

            $errors = collect($validator->errors())->flatten(1)[0];
            if (is_numeric($errors)) {

                $errors_arr = [
                    407 => 'Failed,The image type must be an jpg or png or jpeg.',
                    408 => 'Failed,The file uploaded must be an image'
                ];

                $code = collect($validator->errors())->flatten(1)[0];
                return self::returnResponseDataApi(null, $errors_arr[$errors] ?? 500, $code);
            }
            return self::returnResponseDataApi(null, $validator->errors()->first(), 422);
        }
        $user = Auth::guard('user-api')->user();

        if ($image = $request->file('image')) {
            $destinationPath = 'user/';
            $file = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $file);
            $request['image'] = "$file";

            if (file_exists(public_path($user->image)) && $user->image != null) {
                unlink(public_path($user->image));
            }
        }

        $user->update([
            'image' => "user/".$file ?? $user->image
        ]);
        $user['token'] = $request->bearerToken();
        return self::returnResponseDataApi(new UserResource($user), "تم تعديل صوره الطالب بنجاح", 200);
    }

    public function home_page(): JsonResponse
    {
        try {

            $userId = userId();

            ############ فتح اول فصل للطالب التابع للمرحله الدراسيه للطالب ########
            $subjectClass = SubjectClass::query()
                ->whereHas('term', function (Builder $builder) {
                    $builder->where('status', '=', 'active')
                        ->where('season_id', '=', getSeasonIdOfStudent());
                })
                ->where('season_id', '=', getSeasonIdOfStudent())
                ->first();

            if (!$subjectClass) {
                return self::returnResponseDataApi(null, "لا يوجد فصول برجاء ادخال عدد من الفصول لفتح اول فصل من القائمه", 404, 404);
            }

            $firstLesson = Lesson::query()
                ->where('subject_class_id', '=', $subjectClass->id)
                ->first();

            if (!$firstLesson) {
                return self::returnResponseDataApi(null, "لا يوجد قائمه دروس لفتح اول درس", 404, 404);
            }


            ################ فتح اول درس تابع لهذا الفصل #############
            $subjectClassOpened = OpenLesson::query()
                ->where('user_id', '=', userId())
                ->where('subject_class_id', '=', $subjectClass->id);

            $lessonOpened = OpenLesson::query()
                ->where('user_id', '=', userId())
                ->where('lesson_id', '=', $firstLesson->id);

            if (!$subjectClassOpened->exists() && !$lessonOpened->exists()) {
                OpenLesson::create(['user_id' => userId(), 'subject_class_id' => $subjectClass->id]);
                OpenLesson::create(['user_id' => userId(), 'lesson_id' => $firstLesson->id]);
            }

            ################# ابعتلي اخر امتحان لايف تبع المرحله الدراسيه للطالب ############
            $liveExam = LifeExam::query()
                ->select('id', 'name_ar', 'name_en', 'date_exam', 'time_start', 'time_end', 'degree', 'season_id', 'term_id', 'quiz_minute')
                ->whereHas('term', fn (Builder $builder) =>
                $builder->where('status', '=', 'active')
                    ->where('season_id', '=', getSeasonIdOfStudent()))
                ->where('season_id', '=',getSeasonIdOfStudent())
                ->latest()
                ->first();

            if($liveExam){

                $nowLiveExamModel = Carbon::now();
                $startLiveExamModel = Carbon::createFromTimeString($liveExam->time_start);
                $endLiveExamModel = Carbon::createFromTimeString($liveExam->time_end);

                $liveExamDegree = ExamDegreeDepends::query()
                    ->where('user_id', '=', Auth::guard('user-api')->id())
                    ->where('life_exam_id', '=', $liveExam->id)
                    ->first();


                ########### جلب الامتحان الالايف قبل ميعاد الامتحان كمؤشر للطالب############
                if(Carbon::now()->format('Y-m-d') < $liveExam->date_exam){

                    $data['life_exam'] = null;
                    $data['live_model'] = $liveExam;

                ######## لو الطالب امتحن هذا الامتحان #######
                }elseif ($liveExamDegree){

                    $data['life_exam'] = null;
                    $data['live_model'] = null;

                 #### لو الامتحان الطالب ممتحنوش ومتاح ما بين الموعدين ##############
                }elseif (date('Y-m-d') == $liveExam->date_exam && $nowLiveExamModel->isBetween($startLiveExamModel,$endLiveExamModel)){


                    $data['life_exam'] = $liveExam->id;
                    $data['live_model'] = $liveExam;


                ####### لو الامتحان لسه مبدائشي ويكون موعد نهايه الامتحان اقل من التوقيت الحالي ##############
                }elseif (!$nowLiveExamModel->isBetween($startLiveExamModel,$endLiveExamModel) && date('Y-m-d') == $liveExam->date_exam && $startLiveExamModel->gt(Carbon::now())){

                    $data['life_exam'] = null;
                    $data['live_model'] = $liveExam;


                }else{

                    $data['life_exam'] = null;
                    $data['live_model'] = null;
                }
            }else{

                $data['life_exam'] = null;
                $data['live_model'] = null;
            }


            ############### فصول المرحله الدراسيه للطالب ########
            $classes = SubjectClass::query()
                ->whereHas('term', fn (Builder $builder) =>
                $builder->where('status', '=', 'active')
                    ->where('season_id', '=', getSeasonIdOfStudent()))
                ->where('season_id', '=', getSeasonIdOfStudent())
                ->get();


            $sliders = Slider::query()->get();

            ############### فيديوهات المراجعه النهائيه للطالب وتظهر في حاله التفعيل  ########
            $videos_resources = VideoResource::query()
                ->whereHas('term', fn (Builder $builder) =>
                $builder->where('status', '=', 'active')
                    ->where('season_id', '=',getSeasonIdOfStudent()))
                ->where('season_id', '=',getSeasonIdOfStudent())
                ->latest()
                ->get();


            ### فيديوهات الاساسيات لجميع المراحل الدراسيه #############
            $videos_basics = VideoBasic::query()
            ->latest()
            ->get();

            ####### اعدادات اللغه في التطبيق ########
            $setting = Setting::query()->first();

            $user = User::query()
                ->where('id','=',userId())
                ->first();

            $user->update(['access_token' => request()->bearerToken()]);


            ########################### عدد الاشعارات اليوميه للطالب #########################################
            $notifications = Notification::query()
                ->whereDate('created_at','=',date('Y-m-d'))
                ->where(function ($q) use ($userId) {
                    $q->where('season_id', '=',getSeasonIdOfStudent())
                        ->orWhereJsonContains('group_ids', "$userId")
                        ->orWhere('user_id', '=', userId());
                });

                $count =   $notifications->count();

                $listOfNotifications = $notifications
                    ->pluck('id')
                ->toArray();

            $notificationsSeen = NotificationSeenStudent::query()
                ->where('student_id','=', userId())
                ->whereIn('notification_id',$listOfNotifications)
                ->count();

            $data['notification_count'] =     ($count - $notificationsSeen);
            $data['center_status']      =        studentAuth()->center;
            $data['language_active']    =     $setting ? $setting->lang == 'active' ? "active" :"not_active" : null;
            $data['sliders']            =     SliderResource::collection($sliders);
            $data['videos_basics']      =     VideoBasicResource::collection($videos_basics);
            $data['classes']            =     SubjectClassNewResource::collection($classes);
            $data['videos_resources']   =     $setting->videos_resource_active == "active" ? VideoResourceResource::collection($videos_resources) : [];

            return self::returnResponseDataApiWithMultipleIndexes($data, "تم ارسال جميع بيانات الصفحه الرئيسيه", 200);

        } catch (\Exception $exception) {

            return self::returnResponseDataApi(null, $exception->getMessage(), 500);
        }
    }


    public function allClasses(): JsonResponse
    {

        $classes = SubjectClass::query()
        ->whereHas('term', fn (Builder $builder) =>
        $builder->where('status', '=', 'active')
            ->where('season_id', '=', getSeasonIdOfStudent()))
            ->where('season_id', '=',getSeasonIdOfStudent())
            ->get();


        return self::returnResponseDataApi(HomeAllClasses::collection($classes), "تم ارسال جميع الفصول بنجاح", 200);
    }


    public function all_exams(): JsonResponse
    {

        $allExams = AllExam::query()
            ->whereHas('term', fn (Builder $builder) =>
            $builder->where('status', '=', 'active')
                ->where('season_id', '=',getSeasonIdOfStudent()))
            ->where('season_id', '=', getSeasonIdOfStudent())
            ->get();

        return self::returnResponseDataApi(AllExamResource::collection($allExams), "تم الحصول علي جميع الامتحانات الشامله بنجاح", 200);
    }

    public function startYourJourney(Request $request): JsonResponse
    {


        $classes = SubjectClass::query()
            ->whereHas('term', fn (Builder $builder) =>
            $builder->where('status', '=', 'active')
                ->where('season_id', '=',getSeasonIdOfStudent()))
            ->where('season_id', '=',getSeasonIdOfStudent())
            ->get();

        return self::returnResponseDataApi(SubjectClassNewResource::collection($classes), "تم الحصول علي بيانات ابدء رحلتك بنجاح", 200);
    }

    public function videosResources(): JsonResponse
    {

        $resources = VideoResource::query()
            ->whereHas('term', fn (Builder $builder) =>
            $builder->where('status', '=', 'active')
                ->where('season_id', '=',getSeasonIdOfStudent()))
            ->where('season_id', '=',getSeasonIdOfStudent())
            ->get();

        return self::returnResponseDataApi(VideoResourceResource::collection($resources), "تم الحصول علي بيانات المراجعه النهائيه بنجاح", 200);
    }

    public function findExamByClassById($id): JsonResponse
    {

        $class = SubjectClass::query()->where('id', $id)->first();
        if (!$class) {
            return self::returnResponseDataApi(null, "هذا الفصل غير موجود", 404);
        }

        $exams = $class->exams;
        return self::returnResponseDataApi(OnlineExamNewResource::collection($exams), "تم ارسال جميع امتحانات الفصل بنجاح", 200);
    }

    public function add_device_token(Request $request)
    {

        $rules = [
            'token' => 'required',
            'phone_type' => 'required|in:android,ios'
        ];
        $validator = Validator::make($request->all(), $rules, [
            'phone_type.in' => 407,
        ]);


        if ($validator->fails()) {

            $errors = collect($validator->errors())->flatten(1)[0];
            if (is_numeric($errors)) {

                $errors_arr = [
                    407 => 'Failed,The phone type must be an android or ios.',
                ];

                $code = collect($validator->errors())->flatten(1)[0];
                return self::returnResponseDataApi(null, $errors_arr[$errors] ?? 500, $code);
            }
            return self::returnResponseDataApi(null, $validator->errors()->first(), 422);
        }

        $phoneToken = PhoneToken::updateOrCreate(
            ['user_id' => userId()],
            [
            'user_id' => userId(),
            'token' => $request->token,
            'phone_type' => $request->phone_type
        ]);

        $phoneToken->user->token = $request->bearerToken();
        if (isset($phoneToken)) {
            return self::returnResponseDataApi(new PhoneTokenResource($phoneToken), "Token insert successfully", 200);
        }
    }

    public function add_notification(Request $request): JsonResponse
    {

        $rules = [
            'body' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ];
        $validator = Validator::make($request->all(), $rules, [
            'body.string' => 407,
        ]);


        if ($validator->fails()) {

            $errors = collect($validator->errors())->flatten(1)[0];
            if (is_numeric($errors)) {

                $errors_arr = [
                    407 => 'Failed,The body must be an string.',
                ];

                $code = collect($validator->errors())->flatten(1)[0];
                return self::returnResponseDataApi(null, $errors_arr[$errors] ?? 500, $code);
            }
            return self::returnResponseDataApi(null, $validator->errors()->first(), 422);
        }

        $notification = [
            'title' => "اشعار جديد",
            'body' => $request->body,
            'user_type' => "student",
            'user_id' => $request->user_id,
        ];

        Notification::create($notification);

        $this->sendFirebaseNotification(['title' => 'اشعار جديد', 'body' => $request->body],null,$request->user_id,null,true);

        return self::returnResponseDataApi(null, "تم ارسال اشعار جديد", 200);
    }

    public function user_add_screenshot(): JsonResponse
    {

        $studentAddScreenBeforeInApp = UserScreenShot::query()
            ->where('user_id', '=',userId())
            ->first();

        $studentAuth = Auth::guard('user-api')->user();

        //check if student add screen shoot before
        if($studentAddScreenBeforeInApp){

                $studentAddScreenBeforeInApp->update(['count_screen_shots' => $studentAddScreenBeforeInApp->count_screen_shots+=1]);

                if($studentAddScreenBeforeInApp->save()){

                    if($studentAddScreenBeforeInApp->count_screen_shots == 3){

                        $studentAuth->update(['user_status' => 'not_active', 'login_status' => 0]);
                        auth()->guard('user-api')->logout();

                        return self::returnResponseDataApi(null, "تم حظر ذلك الطالب بنجاح وجار تسجيل الخروج من التطبيق", 201);

                    }else{

                        return self::returnResponseDataApi(null, "تم اخذ اسكرين شوت بالتطبيق بواسطه اليوزر", 200);
                    }

                }else{

                    return self::returnResponseDataApi(null, "يوجد خطاء بدخول البيانات برجاء الرجوع لمطور الباك اند", 500);
                }

        //add new screen by student
        }else{

             $studentAddScreenShoot = new UserScreenShot();
             $studentAddScreenShoot->user_id = userId();
             $studentAddScreenShoot->count_screen_shots = 1;
             $studentAddScreenShoot->save();

             if( $studentAddScreenShoot->save()){
                 return self::returnResponseDataApi(null, "تم اخذ اسكرين شوت بالتطبيق بواسطه اليوزر", 200);

             }else{

                 return self::returnResponseDataApi(null, "يوجد خطاء بدخول البيانات برجاء الرجوع لمطور الباك اند", 500);
             }
        }

    }

    public function logout(Request $request): JsonResponse
    {

        try {

            $rules = [
                'token' => 'required|exists:phone_tokens,token',
            ];
            $validator = Validator::make($request->all(), $rules, [
                'token.exists' => 407,
            ]);

            if ($validator->fails()) {

                $errors = collect($validator->errors())->flatten(1)[0];
                if (is_numeric($errors)) {

                    $errors_arr = [
                        407 => 'Failed,The token does not exists.',
                    ];

                    $code = collect($validator->errors())->flatten(1)[0];
                    return self::returnResponseDataApi(null, isset($errors_arr[$errors]) ? $errors_arr[$errors] : 500, $code);
                }
                return self::returnResponseDataApi(null, $validator->errors()->first(), 422);
            }

            PhoneToken::query()->where('token', '=', $request->token)
                ->where('user_id', '=',userId())
                ->delete();

            auth()->guard('user-api')->logout();
            return self::returnResponseDataApi(null, "تم تسجيل الخروج بنجاح", 200);
        } catch (\Exception $exception) {

            return self::returnResponseDataApi(null, $exception->getMessage(), 500);
        }
    }


    public function inviteYourFriends(): JsonResponse
    {

        $setting = Setting::query()->first();
        $data['share'] = lang() == 'ar' ? $setting->share_ar : $setting->share_en;

        return self::returnResponseDataApi($data, "تم الحصول علي معلومات مشاركه الاصدقاء بنجاح", 200);
    }


    public function examCountdown(): JsonResponse
    {

        $examSchedule = ExamSchedule::queryGetExamCountDown();

        if ($examSchedule && date('Y-m-d') < Carbon::parse($examSchedule->date_time)->format('Y-m-d')) {

            $timeFirst  = strtotime(now());
            $timeSecond = strtotime($examSchedule->date_time);
            $differenceInSeconds = $timeSecond - $timeFirst;


            $months = floor($differenceInSeconds / 2592000);
            $hours = floor(($differenceInSeconds % 86400) / 3600);
            $days = floor(($differenceInSeconds % 2592000) / 86400);

            $data['image'] = $examSchedule->image != null ? asset('exam_schedules/' . $examSchedule->image) : asset('exam_schedules/default/default.png');
            $data['title'] = lang() == 'ar' ? $examSchedule->title_ar : $examSchedule->title_en;
            $data['description'] = lang() == 'ar' ? $examSchedule->description_ar : $examSchedule->description_en;
            $data['date_exam'] = Carbon::parse($examSchedule->date_time)->format('Y-m-d');
            $data['months'] = $months;
            $data['days'] =   $days;
            $data['hours'] = $hours;

            return self::returnResponseDataApi($data, "تم الحصول علي بيانات العد التنازلي بنجاح", 200);
        } else {
            return self::returnResponseDataApi(null, "لا يوجد اي عد تنازلي للسنه الدراسيه لهذا الطالب الي الان", 201);
        }
    }


    public function notificationUpdateStatus($id): JsonResponse
    {

        try {

            $notification = Notification::query()
                ->where('id', '=', $id)
                ->first();

            if (!$notification) {
                return self::returnResponseDataApi(null, "هذا الاشعار غير موجود", 404, 404);
            }

            $notificationSeenBefore = NotificationSeenStudent::query()
                ->where('student_id', '=', Auth::guard('user-api')->id())
                ->where('notification_id', '=', $notification->id)
                ->first();

            if (!$notificationSeenBefore) {
                $notificationSeen = NotificationSeenStudent::create([
                    'student_id' => userId(),
                    'notification_id' => $notification->id,
                ]);

                if ($notificationSeen->save()) {
                    return self::returnResponseDataApi(new NotificationResource($notification), "تم تحديث حاله الاشعار بنجاح", 200);
                } else {
                    return self::returnResponseDataApi(null, "يوجد خطاء ما اثناء تحديث حاله الاشعار", 500);
                }
            } else {

                return self::returnResponseDataApi(new NotificationResource($notification), "تم تحديث حاله الاشعار من قبل", 201);
            }
        } catch (\Exception $exception) {

            return self::returnResponseDataApi(null, $exception->getMessage(), 500);
        }
    }
}
