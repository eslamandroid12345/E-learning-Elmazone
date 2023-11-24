<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVideoBasicRequest extends FormRequest{

    public function authorize(): bool
    {
        return true;
    }



    public function rules(): array
    {


            return [
                'name_ar'          => 'required',
                'name_en'          => 'required',
                'background_color' => 'required',
                'time'             => 'required|date_format:H:i:s',
                'video_link'       => 'nullable',
            ];


    }


    public function messages(): array
    {

        return [
            'name_ar.required'          => 'الاسم بالعربي مطلوب',
            'name_en.required'          => 'الاسم بالانجليزي مطلوب',
            'background_color.required' => 'لون الخلفية مطلوب',
            'time.required'             => 'وقت الفيديو مطلوب',
        ];


    }

}
