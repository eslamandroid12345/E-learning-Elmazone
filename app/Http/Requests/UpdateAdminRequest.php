<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'email'     => 'required|email|unique:admins,email,'.$this->id,
            'name'      => 'required',
            'password'  => 'required_without:id'.request()->isMethod('put') ? '' : '|min:6',
            'image'     => 'mimes:jpeg,jpg,png,gif,webp',
        ];
    }

    public function messages(): array
    {
        return [
            'image.mimes'                => 'صيغة الصورة غير مسموحة',
            'name.required'              => 'يجب ادخال الاسم',
            'email.required'             => 'يجب ادخال الإيميل',
            'email.unique'               => 'الإيميل مستخدم من قبل',
            'password.required_without'  => 'يجب ادخال كلمة مرور',
            'password.min'               => 'الحد الادني لكلمة المرور : 6 أحرف',
        ];
    }
}
