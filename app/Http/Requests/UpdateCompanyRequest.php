<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'sector' => 'nullable|string|max:255',
            'currency' => 'required|string|max:255',
            'email' => 'nullable|string|email|',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'proforma_invoice_prefix' => 'nullable|string|max:255',
            'is_price_before_vat' => 'required|integer',

            'is_discount_before_vat' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    if ($this->get('is_price_before_vat') == 0 && $value == 1) {
                        $fail('If Unit Price Method is "After VAT", then Discount Method should be "After Grand Total Price"');
                    }
                },
            ],

            'logo' => 'sometimes|file',
        ];
    }
}
