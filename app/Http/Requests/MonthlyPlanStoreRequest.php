<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MonthlyPlanStoreRequest extends FormRequest
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
            'background_color' => 'required',
            'title_ar' => 'required',
            'title_en' => 'required',
            'description_ar' => 'required',
            'description_en' => 'required',
            'start' => 'required',
            'end' => 'required|after:start',
            'season_id' => 'required',
            'term_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'background_color.required' => 'اللون مطلوب',
            'title_ar.required' => 'العنوان بالعربي مطلوب',
            'title_en.required' => 'العنوان بالانجليزي مطلوب',
            'description_ar.required' => 'الوصف بالعربي مطلوب',
            'description_en.required' => 'الوصف بالانجليزي مطلوب',
            'start.required' => 'تاريخ البداية مطلوب',
            'end.required' => 'تاريخ النهاية مطلوب',
            'end.after' => 'يجب ان يكون تاريخ النهاية بعد تاريخ البداية',
            'season_id.required' => 'الفصل مطلوب',
            'term_id.required' => 'الترم مطلوب',
        ];
    }
}
