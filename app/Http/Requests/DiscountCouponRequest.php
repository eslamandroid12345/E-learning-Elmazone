<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiscountCouponRequest extends FormRequest{


    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        if (request()->isMethod('post')) {

            $rules = [
                'coupon' => 'required',
                'discount_type' => 'required|in:per,value',
                'discount_amount' => 'required',
                'valid_from' => 'required',
                'valid_to' => 'required',
                'total_usage' => 'required|integer'

            ];

        }elseif (request()->isMethod('PUT')) {

            $rules = [
                'coupon' => 'required',
                'discount_type' => 'required|in:per,value',
                'discount_amount' => 'required',
                'valid_from' => 'required',
                'valid_to' => 'required',
                'total_usage' => 'required|integer'


            ];
        }

        return $rules;
    }
}
