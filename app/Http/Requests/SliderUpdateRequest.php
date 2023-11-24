<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SliderUpdateRequest extends FormRequest
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
            'file' => 'sometimes|mimes:png,jpg',
            'link' => 'required|url',
            'type' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'file.mimes' => 'الملف يجب ان يكون بصيغة jpg, png',
            'link.required' => 'الرابط مطلوب',
            'link.url' => 'يجب ان يكون رابط صحيح',
            'type.required' => 'النوع مطلوب',
        ];
    }
}
