<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetDiscountsRequest extends FormRequest
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
            'customer-id' => 'required|exists:customers,id',
            'items' => 'required|array',
            'items.*.product-id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric',
            'items.*.unit-price' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'customer-id.exists' => 'The given customer (id :input) does not exist in our records',
            'items.required' => 'The items field is required',
            'items.array' => 'The items field must be an array',
            'items.*.product-id.exists' => 'The given product (id :input) does not exist in our records'
        ];
    }
}
