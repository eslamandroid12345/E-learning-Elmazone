<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestLifeExam extends FormRequest
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
            'name_ar' => 'required',
            'name_en' => 'required',
            'date_exam' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
            'quiz_minute' => 'required',
            'trying' => 'required',
            'degree' => 'required',
            'season_id' => 'required',
            'term_id' => 'required',
        ];
    }
}
