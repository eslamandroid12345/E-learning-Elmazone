<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLesson extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'title_ar' => 'required',
            'title_en' => 'required',
            'name_ar' => 'required',
            'name_en' => 'required',
            'background_color' => 'required',
            'subject_class_id' => 'required'
        ];
    }
}
