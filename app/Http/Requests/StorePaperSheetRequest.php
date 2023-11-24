<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaperSheetRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {

        if (request()->isMethod('post')) {

            $rules = [
                'name_ar' => 'required',
                'name_en' => 'required',
                'degree' => 'required',
                'season_id' => 'required',
                'term_id' => 'required',
                'from' => 'required|date',
                'to' => 'required|date',
                'date_exam' => 'required|date',

            ];

        }elseif (request()->isMethod('PUT')) {

            $rules = [
                'name_ar' => 'required',
                'name_en' => 'required',
                'degree' => 'required',
                'season_id' => 'required',
                'term_id' => 'required',
                'from' => 'required|date',
                'to' => 'required|date',
                'date_exam' => 'required|date',

            ];
        }

        return $rules;
    }

    public function messages(): array
    {


        if (request()->isMethod('post')) {

            $messages = [
                'name_ar.required' => 'اسم الامتحان باللغه العربيه مطلوب',
                'name_en.required' => 'اسم الامتحان باللغه الانجليزيه مطلوب',
                'degree.required' => 'درجه الامتحان مطلوبه',
                'season_id.required' => 'الصصف الدراسي مطلوب',
                'term_id.required' => 'التيرم مطلوب',
                'from.required' => 'تاريخ بدايه تسجيل الامتحان الورقي مطلوب',
                'to.required' => 'تاريخ نهايه التسجيل في الامتحان الورقي مطلوب',
                'date_exam.required' => 'موعد الامتحان للطلبه',

            ];

        }elseif (request()->isMethod('PUT')) {

            $messages = [
                'name_ar.required' => 'اسم الامتحان باللغه العربيه مطلوب',
                'name_en.required' => 'اسم الامتحان باللغه الانجليزيه مطلوب',
                'degree.required' => 'درجه الامتحان مطلوبه',
                'season_id.required' => 'الصصف الدراسي مطلوب',
                'term_id.required' => 'التيرم مطلوب',
                'from.required' => 'تاريخ بدايه تسجيل الامتحان الورقي مطلوب',
                'to.required' => 'تاريخ نهايه التسجيل في الامتحان الورقي مطلوب',
                'date_exam.required' => 'موعد الامتحان للطلبه',
            ];
        }

        return $messages;



    }
}
