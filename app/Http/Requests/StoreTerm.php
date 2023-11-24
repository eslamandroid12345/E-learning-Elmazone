<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTerm extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name_ar' => 'required',
            'name_en' => 'required',
            'season_id' => 'required',
        ];
    }
}
