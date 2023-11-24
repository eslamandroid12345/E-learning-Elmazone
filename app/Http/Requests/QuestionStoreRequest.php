<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionStoreRequest extends FormRequest
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
            'question' => 'required',
            'difficulty' => 'required',
            'image' => 'nullable',
            'degree' => 'required|numeric',
            'question_type' => 'required',
            'season_id' => 'required',
            'term_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'question.required' => 'السؤال مطلوب',
            'difficulty.required' => 'مستوى الصعوبة مطلوب',
            'degree.required' => 'الدرجة مطلوبة',
            'question_type.required' => 'نوع السؤال مطلوب',
            'season_id.required' => 'الفصل مطلوب',
            'term_id.required' => 'الترم مطلوب',
        ];
    }
}
