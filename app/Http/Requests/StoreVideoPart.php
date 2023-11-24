<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVideoPart extends FormRequest
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
                'lesson_id' => 'required',
                'link' => 'nullable',
                'youtube_link' => 'nullable',
                'video_time' => 'required|date_format:H:i:s',

            ];

        }elseif (request()->isMethod('PUT')) {

            $rules = [
                'name_ar' => 'required',
                'name_en' => 'required',
                'link' => 'nullable',
                'youtube_link' => 'nullable',
                'video_time' => 'required|date_format:H:i:s',


            ];
        }

        return $rules;
    }

    public function messages(): array
    {


        if (request()->isMethod('post')) {

            $messages = [
                'name_ar.required' => 'عنوان فيديو الشرح باللغه العربيه مطلوب',
                'name_en.required' => 'عنوان فيديو الشرح باللغه الانجليزيه مطلوب',
                'link.required' => 'مسار الفيديو مطلوب',
                'video_time.required' => 'توقيت الفيديو مطلوب',
                'lesson_id.required' => 'اختر درس معين لاضافه الفيديو',

            ];

        }elseif (request()->isMethod('PUT')) {

            $messages = [
                'name_ar.required' => 'عنوان فيديو الشرح باللغه العربيه مطلوب',
                'name_en.required' => 'عنوان فيديو الشرح باللغه الانجليزيه مطلوب',
                'video_time.required' => 'توقيت الفيديو مطلوب',
                'lesson_id.required' => 'اختر درس معين لاضافه الفيديو',

            ];
        }

        return $messages;



    }
}
