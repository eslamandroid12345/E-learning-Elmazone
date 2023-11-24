<?php

namespace App\Http\Controllers\Api\Favorites;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllExamNewResource;
use App\Http\Resources\LiveExamFavoriteResource;
use App\Http\Resources\LiveExamResource;
use App\Http\Resources\OnlineExamNewResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\VideoBasicResource;
use App\Http\Resources\VideoPartNewResource;
use App\Http\Resources\VideoPartResource;
use App\Http\Resources\VideoResourceResource;
use App\Models\AllExam;
use App\Models\ExamsFavorite;
use App\Models\LifeExam;
use App\Models\OnlineExam;
use App\Models\VideoBasic;
use App\Models\VideoFavorite;
use App\Models\VideoParts;
use App\Models\VideoResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{

    public function examAddFavorite(Request $request): JsonResponse
    {

        try {
            $rules = [
                'online_exam_id' => 'nullable|exists:online_exams,id',
                'all_exam_id' => 'nullable|exists:all_exams,id',
                'life_exam_id' => 'nullable|exists:life_exams,id',
                'action' => 'required|in:favorite,un_favorite',
            ];
            $validator = Validator::make($request->all(), $rules, [

                'online_exam_id.exists' => 407,
                'all_exam_id.exists' => 408,
                'life_exam_id.exists' => 409,
                'action.in' => 410,

            ]);

            if ($validator->fails()) {
                $errors = collect($validator->errors())->flatten(1)[0];
                if (is_numeric($errors)) {

                    $errors_arr = [
                        407 => 'Failed,Online exam does not exists.',
                        408 => 'Failed,All exam does not exists.',
                        409 => 'Failed,Live exam does not exists.',
                        410 => 'Failed,Action must be an favorite or un_favorite',
                    ];

                    $code = collect($validator->errors())->flatten(1)[0];
                    return self::returnResponseDataApi(null, isset($errors_arr[$errors]) ? $errors_arr[$errors] : 500, $code);
                }
                return self::returnResponseDataApi(null, $validator->errors()->first(), 422);
            }

            if ($request->online_exam_id == null && $request->all_exam_id == null && $request->life_exam_id == null) {

                return self::returnResponseDataApi(null, "يجب اختيار امتحان اونلاين او لايف او شامل للمفضله", 420);

            } else {

                if ($request->online_exam_id != null) {

                    $userFavorite = ExamsFavorite::query()->where([
                        'user_id' => Auth::guard('user-api')->id(),
                        'online_exam_id' => $request->online_exam_id
                    ])
                        ->exists();

                    if ($userFavorite) {

                        $favorite = ExamsFavorite::query()
                            ->where('user_id', '=', Auth::guard('user-api')->id())
                            ->where('online_exam_id', '=', $request->online_exam_id)
                            ->first();

                        $favorite->update([
                            'online_exam_id' => $request->online_exam_id,
                            'action' => $request->action
                        ]);

                        return self::returnResponseDataApi(null, $request->action == 'favorite' ? "تم اضافه الامتحان الاونلاين للمفضله" : "تم حذف الامتحان الاونلاين من المفضله", 200);

                    } else {

                        ExamsFavorite::create([

                            'user_id' => Auth::guard('user-api')->id(),
                            'online_exam_id' => $request->online_exam_id,
                            'action' => $request->action
                        ]);

                        return self::returnResponseDataApi(null, "تم اضافه الامتحان الاونلاين للمفضله", 200);

                    }

                } elseif ($request->life_exam_id != null) {

                    $userFavorite = ExamsFavorite::query()->where([
                        'user_id' => Auth::guard('user-api')->id(),
                        'life_exam_id' => $request->life_exam_id
                    ])
                        ->exists();

                    if ($userFavorite) {

                        $favorite = ExamsFavorite::query()
                            ->where('user_id', '=', Auth::guard('user-api')->id())
                            ->where('life_exam_id', '=', $request->life_exam_id)
                            ->first();

                        $favorite->update([
                            'life_exam_id' => $request->life_exam_id,
                            'action' => $request->action
                        ]);

                        return self::returnResponseDataApi(null, $request->action == 'favorite' ? "تم اضافه الامتحان الايف للمفضله" : "تم حذف الامتحان الايف من المفضله", 200);

                    } else {

                        ExamsFavorite::create([

                            'user_id' => Auth::guard('user-api')->id(),
                            'life_exam_id' => $request->life_exam_id,
                            'action' => $request->action
                        ]);

                        return self::returnResponseDataApi(null, "تم اضافه الامتحان الايف للمفضله", 200);

                    }

                } else {

                    $userFavorite = ExamsFavorite::query()->where([
                        'user_id' => Auth::guard('user-api')->id(),
                        'all_exam_id' => $request->all_exam_id
                    ])
                        ->exists();

                    if ($userFavorite) {

                        $favorite = ExamsFavorite::query()
                            ->where('user_id', '=', Auth::guard('user-api')->id())
                            ->where('all_exam_id', '=', $request->all_exam_id)
                            ->first();

                        $favorite->update([
                            'all_exam_id' => $request->all_exam_id,
                            'action' => $request->action
                        ]);

                        return self::returnResponseDataApi(null, $request->action == 'favorite' ? "تم اضافه الامتحان الشامل للمفضله" : "تم حذف الامتحان الشامل من المفضله", 200);

                    } else {

                        ExamsFavorite::create([
                            'user_id' => Auth::guard('user-api')->id(),
                            'all_exam_id' => $request->all_exam_id,
                            'action' => $request->action
                        ]);

                        return self::returnResponseDataApi(null, "تم اضافه الامتحان  الشامل للمفضله", 200);

                    }
                }
            }


        } catch (\Exception $exception) {

            return self::returnResponseDataApi(null, $exception->getMessage(), 500);
        }

    }

    public function videoAddFavorite(Request $request): JsonResponse
    {


        try {
            $rules = [
                'video_basic_id' => 'nullable|exists:video_basics,id',
                'video_resource_id' => 'nullable|exists:video_resources,id',
                'video_part_id' => 'nullable|exists:video_parts,id',
                'favorite_type' => 'required|in:video_basic,video_resource,video_part',
                'action' => 'required|in:favorite,un_favorite',
            ];
            $validator = Validator::make($request->all(), $rules, [

                'video_basic_id.exists' => 407,
                'video_resource_id.exists' => 408,
                'video_part_id.exists' => 409,
                'favorite_type.in' => 410,
                'action.in' => 411,

            ]);

            if ($validator->fails()) {
                $errors = collect($validator->errors())->flatten(1)[0];
                if (is_numeric($errors)) {

                    $errors_arr = [
                        407 => 'Failed,video_basic does not exists.',
                        408 => 'Failed,video_resource does not exists.',
                        409 => 'Failed,video_part does not exists',
                        410 => 'Failed,favorite_type must be an video_basic or video_resource or video_part',
                        411 => 'Failed,Action must be an favorite or un_favorite',
                    ];

                    $code = collect($validator->errors())->flatten(1)[0];
                    return self::returnResponseDataApi(null, isset($errors_arr[$errors]) ? $errors_arr[$errors] : 500, $code);
                }
                return self::returnResponseDataApi(null, $validator->errors()->first(), 422);
            }

            if ($request->video_basic_id == null && $request->video_resource_id == null && $request->video_part_id == null) {

                return self::returnResponseDataApi(null, "يجب اختيار نوع فيديو للمفضله", 420);

            } else {

       

        $type = $request->favorite_type;

        switch ($type) {
            case 'video_basic':
                $videoIdKey = 'video_basic_id';
                $messagePrefix = 'فيديو الاساسيات';
                break;
            case 'video_resource':
                $videoIdKey = 'video_resource_id';
                $messagePrefix = 'فيديو المراجعة النهائية';
                break;
            case 'video_part':
                $videoIdKey = 'video_part_id';
                $messagePrefix = 'فيديو الشرح';
                break;
            default:
                // Handle the default case or return an error message.
        }
        
        // Rest of the code remains the same, using $videoIdKey, and $messagePrefix.

        $videoFavorite = VideoFavorite::query()
                        ->where('user_id', '=', Auth::guard('user-api')->id())
                        ->where($videoIdKey, '=', $request->$videoIdKey)
                        ->first();

                if ($videoFavorite) {
                    $videoFavorite->update([$videoIdKey => $request->$videoIdKey, 'action' => $request->action]);
                    $message = $request->action == "favorite" ? "تم اضافة $messagePrefix للمفضلة" : "تم حذف من $messagePrefix المفضله";
                    
                } else {

                    VideoFavorite::create([
                        'user_id' => Auth::guard('user-api')->id(),
                        'favorite_type' => $type,
                         $videoIdKey => $request->$videoIdKey,
                        'action' => $request->action
                    ]);
                    $message = "تم اضافة $messagePrefix للمفضلة";
               }

               return self::returnResponseDataApi(null, $message, 200);
        }  
        
    } catch (\Exception $exception) {

        return self::returnResponseDataApi(null, $exception->getMessage(), 500);
    }

    }


    public function favoriteAll(): JsonResponse
    {

       $all_video_favorites = $this->getAllVideosFavorites();
       $all_exam_favorites = $this->getAllExamFavorites();

        return self::returnResponseDataApi(compact('all_video_favorites','all_exam_favorites'), 'تم الحصول علي جميع المفضله للطالب', 200);
    }



    final public function getAllVideosFavorites(): array
    {


        $video_favorites = DB::table('video_favorites')
            ->leftJoin('video_parts','video_parts.id','=','video_part_id')
            ->leftJoin('video_resources','video_resources.id','=','video_resource_id')
            ->leftJoin('video_basics','video_basics.id','=','video_basic_id')
            ->where('user_id','=',auth('user-api')->id())
            ->where('action','=','favorite')
            ->select('video_favorites.id', 'video_parts.name_ar as video_part_name', 'video_resources.name_ar as video_resource_name', 'video_basics.name_ar as video_basic_name', 'video_parts.link as video_part_link', 'video_resources.video_link as video_resource_link', 'video_basics.video_link as video_basic_link', 'video_parts.video_time as video_part_time', 'video_resources.time as video_resource_time', 'video_basics.time as video_basic_time', 'video_favorites.video_part_id', 'video_favorites.video_resource_id', 'video_favorites.video_basic_id','video_parts.background_image as video_part_image')
            ->get();

        $all_video_favorites = [];
        foreach ($video_favorites as $video_favorite){

            $videoData['id'] = $video_favorite->id;
            $videoData['video_id'] = $video_favorite->video_part_name != null  ?  $video_favorite->video_part_id :  ($video_favorite->video_resource_name != null  ?  $video_favorite->video_resource_id : $video_favorite->video_basic_id);
            $videoData['image'] = $video_favorite->video_part_image != null ? asset('videos/images/'.$video_favorite->video_part_image) : asset('default/teacher.png');
            $videoData['time'] = $video_favorite->video_part_name != null  ?  $video_favorite->video_part_time :  ($video_favorite->video_resource_name != null  ?  $video_favorite->video_resource_time : $video_favorite->video_basic_time);
            $videoData['type'] = $video_favorite->video_part_name != null  ?  "video_part" :  ($video_favorite->video_resource_name != null  ?  "video_resource" : "video_basic");
            $videoData['name'] = $video_favorite->video_part_name != null  ?  $video_favorite->video_part_name :  ($video_favorite->video_resource_name != null  ?  $video_favorite->video_resource_name : $video_favorite->video_basic_name);
            $videoData['path'] = $video_favorite->video_part_name != null  ?  asset('videos/'. $video_favorite->video_part_link) :  ($video_favorite->video_resource_name != null  ?  asset('videos_resources/videos/'. $video_favorite->video_resource_link) :  asset('videos_basics/videos/'.  $video_favorite->video_basic_link));
            $all_video_favorites[] = $videoData;
        }

        return $all_video_favorites;

    }



    final public function getAllExamFavorites(): array
    {


        $exam_favorites = DB::table('exams_favorites')
            ->leftJoin('online_exams','online_exams.id','=','online_exam_id')
            ->leftJoin('all_exams','all_exams.id','=','all_exam_id')
            ->leftJoin('life_exams','life_exams.id','=','life_exam_id')
            ->where('user_id','=',auth('user-api')->id())
            ->where('action','=','favorite')
            ->select('exams_favorites.id', 'online_exams.name_ar as online_exam_name','all_exams.name_ar as all_exam_name', 'life_exams.name_ar as life_exam_name', 'exams_favorites.online_exam_id', 'exams_favorites.all_exam_id', 'exams_favorites.life_exam_id', 'online_exams.exam_type as online_exam_type', 'all_exams.exam_type as all_exam_type', 'online_exams.background_color as online_exam_background_color', 'all_exams.background_color as all_exam_background_color', 'online_exams.quize_minute as online_exam_minutes', 'all_exams.quize_minute as all_exam_minutes', 'life_exams.quiz_minute as life_exam_minutes', 'online_exams.answer_pdf_file as online_exam_answer_pdf_file', 'online_exams.answer_video_file as online_exam_answer_video_file', 'all_exams.answer_pdf_file as all_exam_answer_pdf_file', 'all_exams.answer_video_file as all_exam_answer_video_file', 'life_exams.answer_video_file as life_exam_answer_video_file', 'online_exams.pdf_num_questions as online_exam_pdf_num_questions', 'all_exams.pdf_num_questions as all_exam_pdf_num_questions', 'online_exams.pdf_file_upload as online_exam_pdf_file_upload', 'all_exams.pdf_file_upload as all_exam_pdf_file_upload', 'online_exams.type as online_exam_category_exam','online_exams.answer_video_youtube as answer_video_youtube','online_exams.answer_video_is_youtube as answer_video_is_youtube','all_exams.answer_video_youtube as answer_video_youtube_all_exam','all_exams.answer_video_is_youtube as answer_video_is_youtube_all_exam')
            ->get();


        $all_exam_favorites = [];
        foreach ($exam_favorites as $exam_favorite){

            $examData['id'] = $exam_favorite->id;
            $examData['exam_category_type'] = $exam_favorite->online_exam_name != null  ?  ($exam_favorite->online_exam_category_exam == 'class' ? 'subject_class' :  $exam_favorite->online_exam_category_exam) :  ($exam_favorite->all_exam_name != null  ?  "full_exam" : null);
            $examData['pdf_file_upload'] = $exam_favorite->online_exam_name != null  ?  ($exam_favorite->online_exam_type == 'pdf' ? asset('online_exams/pdf_file_uploads/'. $exam_favorite->online_exam_pdf_file_upload) : null ) :  ($exam_favorite->all_exam_name != null  ?  ($exam_favorite->all_exam_type == 'pdf' ? asset('all_exams/pdf_file_uploads/'. $exam_favorite->all_exam_pdf_file_upload) : null ) : null);
            $examData['image'] = $exam_favorite->online_exam_name != null  ?  ($exam_favorite->online_exam_type == 'online' ? asset('default/exam.png') : asset('default/pdf.png')) :  ($exam_favorite->all_exam_name != null  ? ($exam_favorite->all_exam_type == 'all_exam' ? asset('default/exam.png') : asset('default/pdf.png')) : asset('default/exam.png'));
            $examData['num_of_questions'] = $exam_favorite->online_exam_name != null  ?  ($exam_favorite->online_exam_type == 'online' ? DB::table('online_exam_questions')->where('online_exam_id','=',$exam_favorite->online_exam_id)->count() : $exam_favorite->online_exam_pdf_num_questions) :  ($exam_favorite->all_exam_name != null  ?  ($exam_favorite->all_exam_type == 'all_exam' ? DB::table('online_exam_questions')->where('all_exam_id','=',$exam_favorite->all_exam_id)->count() : $exam_favorite->all_exam_pdf_num_questions) : DB::table('online_exam_questions')->where('life_exam_id','=',$exam_favorite->life_exam_id)->count());
            $examData['answer_pdf_file'] = $exam_favorite->online_exam_name != null  ?  asset('online_exams/pdf_answers/'. $exam_favorite->online_exam_answer_pdf_file) :  ($exam_favorite->all_exam_name != null  ?  asset('all_exams/pdf_answers/'. $exam_favorite->all_exam_answer_pdf_file) : null);
            $examData['answer_video_is_youtube'] = $exam_favorite->online_exam_name != null ?  $exam_favorite->answer_video_is_youtube : $exam_favorite->answer_video_is_youtube_all_exam;
            $examData['answer_video_file'] = $exam_favorite->online_exam_name != null  ?  $exam_favorite->answer_video_is_youtube == 1 ? $exam_favorite->answer_video_youtube : asset('online_exams/videos_answers/'. $exam_favorite->online_exam_answer_video_file) :  ($exam_favorite->all_exam_name != null  ?   $exam_favorite->answer_video_is_youtube_all_exam == 1 ? $exam_favorite->answer_video_youtube_all_exam : asset('all_exams/videos_answers/'. $exam_favorite->all_exam_answer_video_file) : asset('live_exam_file_uploads/videos/'. $exam_favorite->life_exam_answer_video_file));
            $examData['name'] = $exam_favorite->online_exam_name != null  ?  $exam_favorite->online_exam_name :  ($exam_favorite->all_exam_name != null  ?  $exam_favorite->all_exam_name : $exam_favorite->life_exam_name);
            $examData['type'] = $exam_favorite->online_exam_name != null  ?  "online_exam" :  ($exam_favorite->all_exam_name != null  ?  "all_exam" : "life_exam");
            $examData['background_color'] = $exam_favorite->online_exam_name != null  ?  $exam_favorite->online_exam_background_color :  ($exam_favorite->all_exam_name != null  ?  $exam_favorite->all_exam_background_color : "#FFEAD7");
            $examData['quiz_minutes'] = $exam_favorite->online_exam_name != null  ?  $exam_favorite->online_exam_minutes :  ($exam_favorite->all_exam_name != null  ?  $exam_favorite->all_exam_minutes : $exam_favorite->life_exam_minutes);
            $examData['exam_type'] = $exam_favorite->online_exam_name != null  ?  $exam_favorite->online_exam_type :  ($exam_favorite->all_exam_name != null  ?  $exam_favorite->all_exam_type : "life_exam");
            $examData['exam_id'] = $exam_favorite->online_exam_name != null  ?  $exam_favorite->online_exam_id :  ($exam_favorite->all_exam_name != null  ?  $exam_favorite->all_exam_id : $exam_favorite->life_exam_id);
            $all_exam_favorites[] = $examData;
        }

        return $all_exam_favorites;

    }
}
