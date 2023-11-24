<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVideoBasic extends FormRequest
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
            'background_color' => 'required',
            'time' => 'required',
            'video_link' => 'nullable',
        ];
    }
    public function messages()
    {
        return [
            'name_ar.required' => 'الاسم بالعربي مطلوب',
            'name_en.required' => 'الاسم بالانجليزي مطلوب',
            'background_color.required' => 'لون الخلفية مطلوب',
            'time.required' => 'وقت الفيديو مطلوب',
            'video_link.required' => 'لينك الفيديو مطلوب',
        ];
    }
}
