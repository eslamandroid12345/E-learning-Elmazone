<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectClasses extends FormRequest
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
                'title_ar' => 'required',
                'title_en' => 'required',
                'name_ar' => 'required',
                'name_en' => 'required',
                'term_id' => 'required',
                'season_id' => 'required',
                'background_color' => 'required',
                'note' => 'nullable',
                'image' => 'image|nullable',

            ];

        }elseif (request()->isMethod('PUT')) {

            $rules = [
                'title_ar' => 'required',
                'title_en' => 'required',
                'name_ar' => 'required',
                'name_en' => 'required',
                'term_id' => 'required',
                'season_id' => 'required',
                'background_color' => 'required',
                'note' => 'nullable',
                'image' => 'image|nullable',

            ];
        }

        return $rules;
    }
}
