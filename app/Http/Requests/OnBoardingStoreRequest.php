<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OnBoardingStoreRequest extends FormRequest
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
            'title_ar' => 'required',
            'title_en' => 'required',
            'description_ar' => 'required',
            'description_en' => 'required',
            'image' => 'required|mimes:png,jpg',
        ];
    }

    public function messages()
    {
        return [
            'title_ar.required' => 'العنوان بالعربي مطلوب',
            'title_en.required' => 'العنوان بالانجليزي مطلوب',
            'description_ar.required' => 'الوصف بالعربي مطلوب',
            'description_en.required' => 'الوصف بالانجليزي مطلوب',
            'image.required' => 'الصورة مطلوب',
            'image.mimes' => 'يجب ان الصورة من امتداد jpg, png',
        ];
    }
}
