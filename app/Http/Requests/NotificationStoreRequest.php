<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => 'required',
            'body' => 'required',
            'season_id' => 'required',
            'image' => 'nullable|image',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان الاشعار مطلوب',
            'body.required' => 'محتوي الرساله الذي تريد ارساله للطلبه مطلوب',
            'season_id.required' => 'يرجي اختيار الفصل الدراسي للمرحله الذي تريد لها ارسال الاشعار',
            'image.image' => 'الصوره المرفقه مع الاشعار يجب ان تكون صوره',
        ];
    }
}
