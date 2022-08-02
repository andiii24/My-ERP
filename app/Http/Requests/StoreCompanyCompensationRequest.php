<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCompanyCompensationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'compensation' => ['required', 'array'],
            'compensation.*.depends_on' => ['nullable', 'integer', new MustBelongToCompany('company_compensations')],
            'compensation.*.name' => ['required', 'string', 'max:255'],
            'compensation.*.type' => ['required', 'string', 'max:255', Rule::In(['earning', 'deduction'])],
            'compensation.*.is_taxable' => ['required', 'boolean'],
            'compensation.*.is_adjustable' => ['required', 'boolean'],
            'compensation.*.can_be_inputted_manually' => ['required', 'boolean'],
            'compensation.*.percentage' => ['nullable', 'numeric', 'required_unless:depends_on,null', 'gt:0', 'max:100'],
            'compensation.*.default_value' => ['nullable', 'numeric'],
        ];
    }
}