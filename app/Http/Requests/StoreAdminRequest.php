<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name'      => 'required',
            'email'     => 'required|email|unique:admins,email',
            'password'  => 'required|min:6',
            'roles'     => 'required',
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
            'password.required'          => 'يجب ادخال كلمة مرور',
            'roles.required'             => 'يجب ادخال صلاحيه للادمن',
            'password.min'               => 'الحد الادني لكلمة المرور : 6 أحرف',
        ];
    }
}
