<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdjustmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('adjustments', $this->route('adjustment')->id),
                Rule::excludeIf(!userCompany()->isEditingReferenceNumberEnabled())],
            'adjustment' => ['required', 'array'],
            'adjustment.*.warehouse_id' => ['required', 'integer', Rule::in(authUser()->getAllowedWarehouses('adjustment')->pluck('id'))],
            'adjustment.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'adjustment.*.is_subtract' => ['required', 'integer'],
            'adjustment.*.quantity' => ['required', 'numeric', 'gt:0'],
            'adjustment.*.reason' => ['required', 'string'],
            'issued_on' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
