<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestPapelSheetExam extends FormRequest
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
            'description' => 'required',
            'degree' => 'required',
            'season_id' => 'required',
            'term_id' => 'required',
            'from' => 'required',
            'to' => 'required',
            'date_exam' => 'required',
        ];
    }
}
