<?php

namespace App\Http\Controllers\Api\Notes;

use App\Http\Controllers\Controller;
use App\Http\Resources\FilterNotesDateResource;
use App\Http\Resources\MonthlyPlanResource;
use App\Http\Resources\NoteResource;
use App\Models\MonthlyPlan;
use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller{


   public function noteAddByStudent(Request $request): JsonResponse{

       try {
           $rules = [
               'title' => 'nullable|max:255',
               'note' => 'required',
               'note_date' => 'required|date|date_format:Y-m-d',
           ];
           $validator = Validator::make($request->all(), $rules, [
               'note_date.date_format' => 407,
           ]);

           if ($validator->fails()) {
               $errors = collect($validator->errors())->flatten(1)[0];
               if (is_numeric($errors)) {

                   $errors_arr = [
                       407 => 'Failed,The note_date must be an date format Y-m-d.',
                   ];

                   $code = collect($validator->errors())->flatten(1)[0];
                   return self::returnResponseDataApi(null, isset($errors_arr[$errors]) ? $errors_arr[$errors] : 500, $code);
               }
               return self::returnResponseDataApi(null, $validator->errors()->first(), 422);
           }


           $noteAdd = new Note();
           $noteAdd->title = $request->title;
           $noteAdd->note = $request->note;
           $noteAdd->note_date = $request->note_date;
           $noteAdd->user_id = Auth::guard('user-api')->id();
           $noteAdd->save();

           if($noteAdd->save()){

               return self::returnResponseDataApi(new NoteResource($noteAdd),"تم تسجيل الملاحظه بنجاح", 422);
           }else{
               return self::returnResponseDataApi(null,"يوجد خطاء اثناء تسجيل الملاحظه برجاء الرجوع لمطور الباك اند", 420);
           }


       } catch (\Exception $exception) {

           return self::returnResponseDataApi(null, $exception->getMessage(), 500);
       }



   }

    public function noteAllByDate(Request $request){


        try {

            $rules = [
                'date' => 'required|date|date_format:Y-m-d',
            ];
            $validator = Validator::make($request->all(), $rules, [
                'date.format' => 407,
            ]);

            if ($validator->fails()) {

                $errors = collect($validator->errors())->flatten(1)[0];
                if (is_numeric($errors)) {

                    $errors_arr = [
                        407 => 'Failed,The date must be an Y-m-d',
                    ];

                    $code = collect($validator->errors())->flatten(1)[0];
                    return self::returnResponseDataApi(null, isset($errors_arr[$errors]) ? $errors_arr[$errors] : 500, $code);
                }
                return self::returnResponseDataApi(null, $validator->errors()->first(), 422);
            }

            if($request->has('date')){
                if(Note::where('note_date','=',$request->date)->where('user_id','=',Auth::guard('user-api')->id())->exists()){

                    $notes = Note::where('note_date','=',$request->date) ->where('user_id','=',Auth::guard('user-api')->id())->get();

                    return self::returnResponseDataApi(NoteResource::collection($notes), "تم ارسال جميع الملاحظات في هذا اليوم بنجاح", 200);

                }else{

                    return self::returnResponseDataApi(null, "لا يوجد ملاحظات في هذا اليوم", 200);
                }
            }

        } catch (\Exception $exception) {

            return self::returnResponseDataApi(null, $exception->getMessage(), 500);
        }


    }

    public function datesOfNotes(): JsonResponse
    {

        $dates = Note::query()
        ->where('user_id','=',Auth::guard('user-api')->id())
            ->select('id','note_date','user_id')
            ->get();

        if($dates->count() > 0){

            return self::returnResponseDataApi(FilterNotesDateResource::collection($dates),"تم الحصول علي جميع تواريخ ملاحظات الطالب", 200);
        }else{

            return self::returnResponseDataApi(null,"لا يوجد اي سجلات توارخ لملاحظات ذلك الطالب", 419);
        }


    }


    public function noteDelete($id): JsonResponse{

       $note = Note::query()
        ->where('id','=',$id)
           ->first();

       if(!$note){
           return self::returnResponseDataApi(null,"الملاحظه غير موجوده", 404,404);
       }

       if($note->user_id != Auth::guard('user-api')->id()){
           return self::returnResponseDataApi(null,"لا يوجد لديك صلاحيه لحذف تلك الملاحظه", 405);

       }

       $note->delete();
        return self::returnResponseDataApi(null,"تم حذف الملاحظه بنجاح", 200);


    }

}
