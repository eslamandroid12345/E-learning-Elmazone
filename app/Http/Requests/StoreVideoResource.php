<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVideoResource extends FormRequest
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


        if (request()->isMethod('post')) {

            $rules = [
                'name_ar' => 'required',
                'name_en' => 'required',
                'image' => 'required_if:type,video',
                'background_color' => 'required',
                'time' => 'required_if:type,video',
                'video_link' => 'nullable',
                'type' => 'required',
                'pdf_file' => 'required_if:type,pdf',
                'season_id' => 'required',
                'term_id' => 'required',

            ];

        }elseif (request()->isMethod('PUT')) {

            $rules = [
                'name_ar' => 'required',
                'name_en' => 'required',
                'image' => 'nullable',
                'background_color' => 'required',
                'time' => 'required_if:type,video',
                'video_link' => 'nullable',
                'type' => 'required',
                'pdf_file' => 'required_if:type,pdf',
                'season_id' => 'required',
                'term_id' => 'required',
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name_ar.required' => 'الاسم بالعربي مطلوب',
            'name_en.required' => 'الاسم بالانجليزي مطلوب',
            'image.image' => 'يجب ان تكون صورة',
            'image.required' => 'الصورة مطلوبة',
            'background_color.required' => 'اللون مطلوب',
            'time.required' => 'الوقت مطلوب',
            'type.required' => 'النوع مطلوب',
            'season_id.required' => 'الصف مطلوب',
            'term_id.required' => 'الترم مطلوب',
        ];
    }
}
