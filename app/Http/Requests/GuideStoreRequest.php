<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuideStoreRequest extends FormRequest
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
            'title_ar' => 'required',
            'title_en' => 'required',
            'description_ar' => 'required',
            'description_en' => 'required',
            'file' => 'required',
            'icon' => 'required|mimes:jpg,jpeg,png',
            'background_color' => 'required',
            'season_id' => 'required',
            'term_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'title_ar.required' => 'العنوان بالعربي مطلوب',
            'title_en.required' => 'العنوان بالانجليزي مطلوب',
            'description_ar.required' => 'سجل ملاحظاتك عن هذا المرجع باللغه العربيه',
            'description_en.required' => 'سجل ملاحظاتك عن هذا المرجع باللغه الانجليزيه',
            'file.required' => 'الملف مطلوب',
            'icon.required' => 'الايقونة مطلوبة',
            'background_color.required' => 'لون الخلفية مطلوب',
            'season_id.required' => 'فصل مطلوب',
            'term_id.required' => 'الترم مطلوب',
        ];
    }
}
