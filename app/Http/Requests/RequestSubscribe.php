<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestSubscribe extends FormRequest
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
            'term_id' => 'required',
            'season_id' => 'required',
            'price_in_center' => 'required',
            'price_out_center' => 'required',
            'month' => 'required',
            'free' => 'required'
        ];
    }
}
