<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamScheduleRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        if (request()->isMethod('post')) {

            $rules = [
                'title_ar' => 'required',
                'title_en' => 'required',
                'description_ar' => 'required',
                'description_en' => 'required',
                'term_id' => 'required',
                'season_id' => 'required',
                'date_time' => 'required',
                'image' => 'image|nullable',

            ];

        }elseif (request()->isMethod('PUT')) {

            $rules = [
                'title_ar' => 'required',
                'title_en' => 'required',
                'description_ar' => 'required',
                'description_en' => 'required',
                'term_id' => 'required',
                'season_id' => 'required',
                'date_time' => 'required',
                'image' => 'image|nullable',


            ];
        }

        return $rules;
    }
}
