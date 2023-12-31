<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionUpdateRequest extends FormRequest
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
            'question' => 'nullable',
            'difficulty' => 'required',
            'image' => 'nullable',
            'degree' => 'required|numeric',
            'season_id' => 'required',
            'term_id' => 'required',

        ];
    }

    public function messages(): array
    {
        return [
            'difficulty.required' => 'مستوى الصعوبة مطلوب',
            'degree.required' => 'الدرجة مطلوبة',
            'season_id.required' => 'الفصل مطلوب',
            'term_id.required' => 'الترم مطلوب',

        ];
    }
}
