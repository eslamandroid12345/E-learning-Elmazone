<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\ReportRepositoryInterface;
use App\Http\Resources\ReportApiResource;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReportRepository extends ResponseApi implements ReportRepositoryInterface {


    public function studentAddReport(Request $request): JsonResponse
    {

        try {

            $rules = [
                'report'            => 'required',
                'type'              => 'required|in:video_part,video_basic,video_resource',
                'video_part_id'     => 'nullable|integer|exists:video_parts,id',
                'video_basic_id'    => 'nullable|integer|exists:video_basics,id',
                'video_resource_id' => 'nullable|integer|exists:video_resources,id',
            ];
            $validator = Validator::make($request->all(), $rules, [
                'type.in'           => 407,
            ]);

            if ($validator->fails()) {
                $errors = collect($validator->errors())->flatten(1)[0];
                if (is_numeric($errors)) {

                    $errors_arr = [
                        407 => 'Failed,The type of video must be an video_part or video_basic or video_resource',
                    ];

                    $code = collect($validator->errors())->flatten(1)[0];
                    return self::returnResponseDataApi(null, isset($errors_arr[$errors]) ? $errors_arr[$errors] : 500, $code);
                }
                return self::returnResponseDataApi(null, $validator->errors()->first(), 422);
            }

            if ($request->video_part_id === null && $request->video_basic_id === null && $request->video_resource_id === null) {
                return self::returnResponseDataApi(null, "يجب عليك ارفاق الفيديو الذي تم البلاغ عنه", 407);
            }

            $report = Report::create([
                 'report'            => $request->report,
                 'user_id'           => userId(),
                 'type'              => $request->type,
                 'video_part_id'     => $request->video_part_id ?? null,
                 'video_basic_id'    => $request->video_basic_id ?? null,
                 'video_resource_id' => $request->video_resource_id ?? null,
            ]);

            if($report->save()){
                return self::returnResponseDataApi(new ReportApiResource($report),"تم رفع البلاغ بنجاح",200);

            }else{
                return self::returnResponseDataApi(null,"يوجد خطاء ما اثناء ادخال البيانات",500);

            }

        }catch (\Exception $exception) {

            return self::returnResponseDataApi(null, $exception->getMessage(), 500);
        }

    }

    public function allByStudent():JsonResponse{

        $reports = Report::query()
        ->where('user_id','=',Auth::guard('user-api')->id())
            ->get();

        if($reports->count() > 0){
            return self::returnResponseDataApi(ReportApiResource::collection($reports),"تم الحصول علي جميع بلاغات الطالب",200);

        }else{

            return self::returnResponseDataApi(null, "لا يوجد اي بلاغات للطالب", 201);
        }
    }

    public function delete($id):JsonResponse{

        $report = Report::where('id','=',$id)->first();
        if(!$report){
            return self::returnResponseDataApi(null, "البلاغ غير موجود", 404);

        }

        if($report->user_id != Auth::guard('user-api')->id()){
            return self::returnResponseDataApi(null, "لا يوجد لديك صلاحيه لحذف هذا البلاغ", 407);

        }else{
            $report->delete();
            return self::returnResponseDataApi(null, "تم حذف البلغ بنجاح", 200);

        }
    }
}
