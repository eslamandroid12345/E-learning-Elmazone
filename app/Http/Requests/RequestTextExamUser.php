<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestTextExamUser extends FormRequest
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
    public function rules()
    {
        return [
            'user_id' => 'required',
            'question_id' => 'required',
            'online_exam_id' => 'required',
            'all_exam_id' => 'required',
            'answer' => 'required',
            'image' => 'required',
            'audio' => 'required',
            'answer_type' => 'required',
            'status' => 'required',
        ];
    }
}
