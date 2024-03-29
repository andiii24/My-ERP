<?php

namespace App\Http\Requests;

use App\Models\Employee;
use App\Rules\CanEditReferenceNumber;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeTransferRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('employee_transfers'), new CanEditReferenceNumber('employee_transfers')],
            'issued_on' => ['required', 'date'],
            'employeeTransfer' => ['required', 'array'],
            'employeeTransfer.*.employee_id' => ['required', 'integer', 'distinct', new MustBelongToCompany('employees'), function ($attribute, $value, $fail) {
                if (!authUser()->getAllowedWarehouses('hr')->where('id', Employee::firstWhere('id', $value)->user->warehouse_id)->count()) {
                    $fail('You do not have permission to create an employee transfer request for this employee.');
                }
            }],
            'employeeTransfer.*.warehouse_id' => ['required', 'integer', new MustBelongToCompany('warehouses')],
        ];
    }
}
