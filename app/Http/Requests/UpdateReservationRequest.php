<?php

namespace App\Http\Requests;

use App\Rules\CheckCustomerCreditLimit;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use App\Rules\ValidateBackorder;
use App\Rules\ValidatePrice;
use App\Rules\VerifyCashReceivedAmountIsValid;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReservationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('reservations', $this->route('reservation')->id),
                Rule::excludeIf(!userCompany()->isEditingReferenceNumberEnabled())],
            'reservation' => ['required', 'array'],
            'reservation.*.product_id' => ['required', 'integer', new MustBelongToCompany('products'), new ValidateBackorder],
            'reservation.*.warehouse_id' => ['required', 'integer', Rule::in(authUser()->getAllowedWarehouses('sales')->pluck('id'))],
            'reservation.*.unit_price' => ['nullable', 'numeric', new ValidatePrice],
            'reservation.*.quantity' => ['required', 'numeric', 'gt:0'],
            'reservation.*.description' => ['nullable', 'string'],
            'reservation.*.discount' => ['nullable', 'numeric', 'min:0', 'max:100'],

            'customer_id' => ['nullable', 'integer', new MustBelongToCompany('customers'),
                Rule::when(
                    !$this->route('reservation')->isCancelled() && !$this->route('reservation')->isConverted(),
                    new CheckCustomerCreditLimit(
                        $this->get('discount'),
                        $this->get('reservation'),
                        $this->get('payment_type'),
                        $this->get('cash_received_type'),
                        $this->get('cash_received')
                    )
                ),
            ],

            'contact_id' => ['nullable', 'integer', new MustBelongToCompany('contacts')],
            'issued_on' => ['required', 'date'],
            'expires_on' => ['required', 'date', 'after_or_equal:issued_on'],
            'payment_type' => ['required', 'string', function ($attribute, $value, $fail) {
                if ($value == 'Credit Payment' && is_null($this->get('customer_id'))) {
                    $fail('Credit Payment without customer is not allowed, please select a customer.');
                }
            },
            ],

            'cash_received_type' => ['required', 'string', function ($attribute, $value, $fail) {
                if ($this->get('payment_type') != 'Credit Payment' && $value != 'percent') {
                    $fail('When payment type is not "Credit Payment", the type should be "Percent".');
                }
            },
            ],

            'description' => ['nullable', 'string'],

            'cash_received' => ['bail', 'required', 'numeric', 'gte:0',
                new VerifyCashReceivedAmountIsValid(
                    $this->get('payment_type'),
                    $this->get('discount'),
                    $this->get('reservation'),
                    $this->get('cash_received_type')
                ),
            ],

            'due_date' => ['nullable', 'date', 'after:issued_on', 'required_if:payment_type,Credit Payment', 'prohibited_if:payment_type,Cash Payment'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'bank_name' => ['nullable', 'string', 'prohibited_if:payment_type,Cash Payment,Credit Payment'],
            'reference_number' => ['nullable', 'string', 'prohibited_if:payment_type,Cash Payment,Credit Payment'],
        ];
    }
}
