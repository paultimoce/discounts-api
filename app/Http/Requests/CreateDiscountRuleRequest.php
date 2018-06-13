<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDiscountRuleRequest extends FormRequest
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
            'type' => 'required|exists:discount_types,handle',
            'threshold' => 'numeric|required_if:type,percent_discount_when_total_amount_over_threshold|required_if:type,free_product_when_quantity_of_certain_category_over_threshold',
            'percentage' => 'numeric|required_if:type,percent_discount_when_total_amount_over_threshold|required_if:type,percentage_discount_when_quantity_of_certain_category_over_threshold',
            'category' => 'numeric|required_if:type,free_product_when_quantity_of_certain_category_over_threshold|required_if:type,percentage_discount_when_quantity_of_certain_category_over_threshold',
            'quantity' => 'numeric|required_if:type,percentage_discount_when_quantity_of_certain_category_over_threshold',
            'operator' => 'required_if:type,percent_discount_when_total_amount_over_threshold|required_if:type,percentage_discount_when_quantity_of_certain_category_over_threshold|in:more,less,equal,at_least,at_most',
            'applies' => 'required_if:type,percent_discount_when_total_amount_over_threshold|required_if:type,percentage_discount_when_quantity_of_certain_category_over_threshold|in:whole_order,cheapest_product',

        ];
    }
}
