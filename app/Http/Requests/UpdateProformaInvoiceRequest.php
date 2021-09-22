<?php

namespace App\Http\Requests;

use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProformaInvoiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'prefix' => ['nullable', 'string'],
            'code' => ['required', 'string', new UniqueReferenceNum('proforma_invoices', $this->route('proforma_invoice')->id)],
            'customer_id' => ['nullable', 'integer'],
            'issued_on' => ['required', 'date'],
            'expires_on' => ['nullable', 'date', 'after_or_equal:issued_on'],
            'terms' => ['nullable', 'string'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'proformaInvoice' => ['required', 'array'],
            'proformaInvoice.*.product_id' => ['required', 'string'],
            'proformaInvoice.*.quantity' => ['required', 'numeric', 'min:1'],
            'proformaInvoice.*.unit_price' => ['required', 'numeric'],
            'proformaInvoice.*.discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'proformaInvoice.*.specification' => ['nullable', 'string'],
        ];
    }
}
