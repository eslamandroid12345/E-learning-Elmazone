<?php

namespace App\Http\Controllers\Api\Instruction;
use App\Http\Controllers\Controller;
use App\Http\Resources\AllExamNewResource;
use App\Http\Resources\InstructionNewResource;
use App\Http\Resources\UserFullDegreeDetailsNewResource;
use App\Models\AllExam;
use App\Models\ExamDegreeDepends;
use App\Models\OnlineExam;
use App\Models\Timer;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InstructionController extends Controller{

    public function instructionExamDetails(Request $request,$id): JsonResponse{

        try {
            $rules = [
                'type' => 'required|in:online_exam,all_exam',
            ];
            $validator = Validator::make($request->all(), $rules, [
                'type.in' => 407,
            ]);

            if ($validator->fails()) {
                $errors = collect($validator->errors())->flatten(1)[0];
                if (is_numeric($errors)) {

                    $errors_arr = [
                        407 => 'Failed,The type of exam online_exam or all_exam',
                    ];

                    $code = collect($validator->errors())->flatten(1)[0];
                    return self::returnResponseDataApi(null, isset($errors_arr[$errors]) ? $errors_arr[$errors] : 500, $code);
                }
                return self::returnResponseDataApi(null, $validator->errors()->first(), 422);
            }
            if ($request->type == 'online_exam') {

                $exam = OnlineExam::where('id', $id)->first();
                if (!$exam) {
                    return self::returnResponseDataApi(null, "الامتحان الاونلاين غير موجود", 404);
                }

                $theHighestDegree = ExamDegreeDepends::query()
                    ->where('online_exam_id','=',$exam->id)
                    ->where('exam_depends','=','yes')
                    ->orderBy('full_degree','desc')
                    ->latest()
                    ->first();

                if($theHighestDegree){

                    $user =  new UserFullDegreeDetailsNewResource($theHighestDegree->user);
                    $user->degree = $theHighestDegree->full_degree;
                    $user->per = ($theHighestDegree->full_degree / $theHighestDegree->online_exam->degree) * 100;
                    $user->time_exam = $theHighestDegree->created_at->diffForHumans();
                    $time = Timer::query()
                        ->where('online_exam_id','=',$exam->id)
                        ->where('user_id','=',$user->id)
                        ->latest()
                        ->first();

                    $user->time = $time->timer;
                    $data['user'] = $user;

                    $data['details'] = new InstructionNewResource($exam);
                    return self::returnResponseDataApiWithMultipleIndexes($data, "تم الحصول علي بيانات ارشادات الامتحان الاونلاين بنجاح", 200);

                } else{

                    $data['user'] = null;
                    $data['details'] = new InstructionNewResource($exam);
                    return self::returnResponseDataApiWithMultipleIndexes($data, "تم الحصول علي بيانات ارشادات الامتحان الاونلاين بنجاح", 201);

                }


            } else {




                $exam = AllExam::query()->where('id',$id)->first();
                if (!$exam) {
                    return self::returnResponseDataApi(null, "الامتحان الشامل غير موجود", 404);
                }

                $theHighestDegree = ExamDegreeDepends::query()
                    ->where('all_exam_id','=',$exam->id)
                    ->where('exam_depends','=','yes')
                    ->orderBy('full_degree','desc')
                    ->latest()
                    ->first();


                if($theHighestDegree){
                    $user =  new UserFullDegreeDetailsNewResource($theHighestDegree->user);
                    $user->degree = $theHighestDegree->full_degree;
                    $user->per = ($theHighestDegree->full_degree / $theHighestDegree->all_exam->degree) * 100;
                    $user->time_exam = $theHighestDegree->created_at->diffForHumans();
                    $time = Timer::query()
                        ->where('all_exam_id','=',$exam->id)
                        ->where('user_id','=',$user->id)->latest()
                        ->first();

                    $user->time = $time->timer;
                    $data['user'] = $user;

                    $data['details'] = new InstructionNewResource($exam);
                    return self::returnResponseDataApiWithMultipleIndexes($data, "تم الحصول علي بيانات ارشادات الامتحان الشامل بنجاح", 200);

                }else{

                    $data['user'] = null;
                    $data['details'] = new InstructionNewResource($exam);
                    return self::returnResponseDataApiWithMultipleIndexes($data, "تم الحصول علي بيانات ارشادات الامتحان الشامل  بنجاح", 201);
                }

            }

        }catch (\Exception $exception) {

            return self::returnResponseDataApi(null, $exception->getMessage(), 500);
        }
    }
}
