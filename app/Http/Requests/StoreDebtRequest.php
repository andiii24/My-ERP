<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;

class StoreDebtRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('debts'), function ($attribute, $value, $fail) {
                if ($this->get('code') != nextReferenceNumber('debts') && !userCompany()->isEditingReferenceNumberEnabled()) {
                    $fail('Modifying a reference number is not allowed.');
                }
            }],
            'supplier_id' => ['required', 'integer', new MustBelongToCompany('suppliers')],
            'debt_amount' => ['required', 'numeric', 'gt:0'],
            'issued_on' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after:issued_on'],
            'description' => ['nullable', 'string'],
        ];
    }
}
