<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompensationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'depends_on' => ['nullable', 'integer', new MustBelongToCompany('compensations')],
            'name' => ['required', 'string', 'max:255', 'distinct', Rule::unique('compensations')->where('company_id', userCompany()->id)->where('id', '<>', $this->route('compensation')->id)->withoutTrashed()],
            'type' => ['required', 'string', 'max:255', Rule::In(['earning', 'deduction'])],
            'is_active' => ['required', 'boolean'],
            'is_taxable' => ['required', 'boolean'],
            'is_adjustable' => ['required', 'boolean'],
            'can_be_inputted_manually' => ['required', 'boolean'],
            'percentage' => ['nullable', 'numeric', 'required_unless:depends_on,null'],
            'default_value' => ['nullable', 'numeric'],
            'maximum_amount' => ['nullable', 'numeric'],
        ];
    }
}
