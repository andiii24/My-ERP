<?php

namespace App\Http\Requests;

use App\Models\Pad;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePadRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'abbreviation' => ['required', 'string'],
            'icon' => ['required', 'string'],
            'inventory_operation_type' => ['required', 'string', Rule::in(Pad::INVENTORY_OPERATIONS)],
            'is_approvable' => ['required', 'boolean'],
            'is_closable' => ['required', 'boolean'],
            'is_cancellable' => ['required', 'boolean'],
            'is_printable' => ['required', 'boolean'],
            'has_prices' => ['required', 'boolean'],
            'has_payment_term' => ['required', 'boolean'],
            'is_enabled' => ['required', 'boolean'],
            'module' => ['required', 'string', Rule::in(Pad::MODULES)],
            'features' => ['nullable', 'array', Rule::in((new Pad)->converts())],

            'field' => ['required', 'array'],

            'field.*.is_relational_field' => ['sometimes', 'required', 'boolean', 'required_with:field'],
            'field.*.relationship_type' => ['nullable', 'string', 'required_if:field.*.is_relational_field,1', 'exclude_if:field.*.is_relational_field,0'],
            'field.*.model_name' => ['nullable', 'string', 'required_if:field.*.is_relational_field,1', 'exclude_if:field.*.is_relational_field,0'],
            'field.*.representative_column' => ['nullable', 'string', 'required_if:field.*.is_relational_field,1', 'exclude_if:field.*.is_relational_field,0'],
            'field.*.component_name' => ['nullable', 'string', 'required_if:field.*.is_relational_field,1', 'exclude_if:field.*.is_relational_field,0'],

            'field.*.label' => ['sometimes', 'required', 'string', 'required_with:field'],
            'field.*.icon' => ['sometimes', 'required', 'string', 'required_with:field'],
            'field.*.is_master_field' => ['sometimes', 'required', 'boolean', 'required_with:field'],
            'field.*.is_required' => ['sometimes', 'required', 'boolean', 'required_with:field'],
            'field.*.is_visible' => ['sometimes', 'required', 'boolean', 'required_with:field'],
            'field.*.is_printable' => ['sometimes', 'required', 'boolean', 'required_with:field'],
            'field.*.tag' => ['sometimes', 'required', 'string', 'required_with:field'],
            'field.*.tag_type' => ['nullable', 'string', 'required_if:field.*.tag,input', 'exclude_unless:field.*.tag,input'],
        ];
    }
}
