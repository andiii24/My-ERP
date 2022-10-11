<?php

namespace App\Http\Requests;

use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePriceIncrementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('price_increments')],
            'target_product' => ['required', 'string', Rule::In(['All Products', 'Specific Products', 'Upload Excel'])],
            'price_type' => ['required', 'string', Rule::In(['percent', 'amount'])],
            'price_increment' => ['required', 'numeric', 'gt:0'],
            'product_id' => ['nullable', 'array', 'required_if:target_product,Specific Products'],
        ];
    }
}