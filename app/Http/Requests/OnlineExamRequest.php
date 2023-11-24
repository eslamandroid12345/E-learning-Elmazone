<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OnlineExamRequest extends FormRequest
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
        //required_if:type,individual
        //required_with:pdf_file_upload

        return [
            'name_ar' => 'required',
            'name_en' => 'required',
            'title_result' => 'required_if:exam_type,online',
            'description_result' => 'required_if:exam_type,online',
            'image_result' => 'required_if:exam_type,online',
            'exam_type' => 'required|in:online,pdf',
            'pdf_file_upload' => 'required_if:exam_type,pdf',
            'pdf_num_questions' => 'required_if:exam_type,pdf',
            'answer_pdf_file' => 'nullable',
            'answer_video_file' => 'nullable',
            'date_exam' => 'required',
            'quize_minute' => 'required',
            'trying_number' => 'required',
            'degree' => 'required',
            'term_id' => 'required',
            'season_id' => 'required',
            'instruction_ar' => 'nullable',
            'instruction_en' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'name_ar.required' => 'اسم الامتحان باللغه العربيه مطلوب',
            'name_en.required' => 'اسم الامتحان باللغه الانجليزيه مطلوب',
            'title_result.required_if' => 'عنوان النصيحه مطلوب في حاله الامتحان اونلاين',
            'description_result.required_if' => 'وصف النصيحه مطلوب في حاله الامتحان اونلاين',
            'image_result.required_if' => 'صوره النصيحه مطلوبه في حاله الامتحان اونلاين',
            'exam_type.required' => 'نوع الامتحان مطلوب',
            'exam_type.in' => 'نوع الامتحان يجب ان يكون اونلاين او ملف ورقي',
            'pdf_file_upload.required_if' => 'الملف الورقي للامتحان مطلوب في حاله اختيار نوع الامتحان pdf',
            'pdf_num_questions.required_if' => 'عدد اسئله الامتحان الورقي مطلوب في حاله اختيار نوع الامتحان pdf',
            'date_exam.required' => 'تاريخ اضافه الامتحان مطلوب',
            'quize_minute.required' => 'توقيت الامتحان مطلوب',
            'trying_number.required' => 'عدد المحاولات علي هذا الامتحان مطلوب',
            'degree.required' => 'درجه هذا الامتحان مطلوب',
            'season_id.required' => 'يجب اختيار الامتحان تبع فصل دراسي معين مثال اولي ثانوي او ثانيه ثانوي',
            'term_id.required' => 'يجب اختيار تيرم معين لاضافه هذا الامتحان',

        ];
    }
}
