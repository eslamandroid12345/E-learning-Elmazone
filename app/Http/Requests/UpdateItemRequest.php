<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
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
        return [
            'title_ar' => 'required',
            'title_en' => 'required',
            'subject_class_id' => 'required',
            'lesson_id' => 'required',
            'file_type' => 'required',
            'month' => 'required',
            'answer_pdf_file' => 'nullable',
            'answer_video_file' => 'nullable',
        ];
    }


    public function messages(): array
    {

       return [
           'title_ar.required' => 'العنوان بالعربي مطلوب',
           'title_en.required' => 'العنوان بالانجليزي مطلوب',
           'subject_class_id.required' => 'الوحدة مطلوب',
           'lesson_id.required' => 'الدرس مطلوب',
           'month.required' => 'ما هو الشهر الذي تريد ان تضيف له هذا المحتوي',
           'file_type.required' => 'نوع الملف مطلوب',
       ];
    }

}
