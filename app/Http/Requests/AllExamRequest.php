<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AllExamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
            'name_ar' => 'required',
            'name_en' => 'required',
            'exam_type' => 'required',
            'pdf_file_upload' => 'nullable',
            'pdf_num_questions' => 'nullable',
            'answer_pdf_file' => 'nullable',
            'answer_video_file' => 'nullable',
            'background_color' => 'nullable',
            'date_exam' => 'required',
            'quize_minute' => 'required',
            'trying_number' => 'required',
            'degree' => 'required',
            'season_id' => 'required',
            'term_id' => 'required',
            'instruction_ar' => 'required',
            'instruction_en' => 'required',
        ];
    }

    public function messages()
    {
        return [
            // message for required values
        ];
    }
}
