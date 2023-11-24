<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestPhoneCommunication extends FormRequest
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
            'phone' => 'required|min:11|max:11',
            'note' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.min' => 'رقم الهاتف 11 على الاقل',
            'phone.max' => 'رقم الهاتف لا يجب ان يكون اكثر من 11 رقم',
        ];
    }
}
