<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUser extends FormRequest
{

    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => "nullable",
            'password' => 'nullable',
            'season_id' => 'required',
            'birth_date' => 'required',
            'country_id' => 'required',
            'phone' => 'required|unique:users,phone,'.$this->id,
            'father_phone' => 'nullable',
            'image' => 'nullable|image',
            'code' => 'required|unique:users,code,'.$this->id,
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'الاسم مطلوب',
            'season_id.required' => 'الصف مطلوب',
            'code.required' => 'كود الطالب مطلوب',
            'birth_date.required' => 'تاريخ الميلاد مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
            'country_id.required' => 'المحافظة مطلوب',
            'date_start_code.required' => 'تاريخ بداية الاشتراك مطلوب',
            'date_end_code.required' => 'تاريخ نهاية الاشتراك مطلوب',
            'phone.unique' => 'هذا الرقم موجود من قبل',
            'code.unique' => 'هذا الكود موجود من قبل حاول مره اخري',


        ];
    }
}
