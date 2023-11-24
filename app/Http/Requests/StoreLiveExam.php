<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLiveExam extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {

        if (request()->isMethod('post')) {

            $rules = [
                'name_ar' => 'required',
                'name_en' => 'required',
                'season_id' => 'required|exists:seasons,id',
                'term_id' => 'required|exists:terms,id',
                'note' => 'nullable',
                'date_exam' => 'required|date',
                'time_start' => 'required|date_format:H:i',
                'time_end' => 'required|date_format:H:i',
                'quiz_minute' => 'required|integer',
                'answer_video_file' => 'nullable|mimes:mp4,mov,ogg',
                'degree' => 'required|integer',

            ];

        }elseif (request()->isMethod('PUT')) {

            $rules = [

                'name_ar' => 'required',
                'name_en' => 'required',
                'season_id' => 'required|exists:seasons,id',
                'term_id' => 'required|exists:terms,id',
                'note' => 'nullable',
                'date_exam' => 'required|date',
                'time_start' => 'required',
                'time_end' => 'required',
                'quiz_minute' => 'required',
                'answer_video_file' => 'nullable|mimes:mp4,mov,ogg',
                'degree' => 'required|integer',

            ];
        }

        return $rules;
    }


    public function messages(): array
    {

        return [

            'name_ar.required' => 'اسم الامتحان الالايف باللغه العربيه مطلوب',
            'name_en.required' => 'اسم الامتحان الالايف باللغه الانجليزيه مطلوب',
            'season_id.required' => 'الصف الدراسي التابع له هذا الامتحان مطلوب',
            'term_id.required' => 'اختر تيرم معين تابع لهذا الصف الدراسي',
            'season_id.exists' => 'هذا الصف الدراسي غير موجود',
            'term_id.exists' => 'هذا التيرم غير موجود',
            'date_exam.required' => 'تاريخ اداء الامتحان مطلوب',
            'date_exam.date' => 'تاريخ الامتحان يجب ان يكون تاريخ',
            'time_start.required' => 'ادخل توقيت بدء هذا الامتحان',
            'time_end.required' => 'ادخل توقيت الانتهاء لهذا الامتحان',
            'time_start.date_format' => 'تاريخ بدء الامتحان يجب ان يكون وقت ليس شيء اخر',
            'time_end.date_format' => 'تاريخ انتهاء الامتحان يجب ان يكون وقت ليس شيء اخر',
            'quiz_minute.required' => 'عدد الدقائق المتاحه لهذا الامتحان',
            'quiz_minute.integer' => 'عدد الدقائق لهذا الامتحان يجب ان تحتوي علي رقم صحيح',
            'degree.required' => 'ادخل درجه هذا الامتحان',
            'degree.integer' => 'درجه هذا الامتحان يجب ان تكون رقم صحيح',
            'answer_video_file.mimes' => 'ملف الاجابه المرفق لهذا الامتحان يجب ان يكون من نوع فيديو',

        ];
    }
}
