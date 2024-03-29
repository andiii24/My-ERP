<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use App\Models\Warehouse;
use App\Rules\MustBelongToCompany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;

class EmployeeImport implements WithHeadingRow, OnEachRow, WithValidation, WithChunkReading
{
    use Importable;

    public function onRow(Row $row)
    {
        if (limitReached('user', Employee::enabled()->count())) {
            session('limitReachedMessage', __('messages.limit_reached', ['limit' => 'users']));

            return;
        }

        $user = User::create([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => Hash::make($row['password']),
            'warehouse_id' => Warehouse::firstWhere('name', $row['warehouse_name'])->id,
        ]);

        $user->employee()->create(
            [
                'position' => $row['job_title'],
                'enabled' => '1',
                'gender' => str()->lower($row['gender']),
                'address' => $row['address'],
                'bank_name' => $row['bank_name'] ?? null,
                'bank_account' => $row['bank_account'] ?? null,
                'tin_number' => $row['tin_number'] ?? null,
                'job_type' => str()->lower($row['job_type']),
                'phone' => $row['phone'],
                'id_type' => str()->lower($row['id_type'] ?? null),
                'id_number' => $row['id_number'] ?? null,
                'date_of_hiring' => $row['date_of_hiring'] ?? null,
                'date_of_birth' => $row['date_of_birth'] ?? null,
                'emergency_name' => $row['emergency_name'] ?? null,
                'emergency_phone' => $row['emergency_phone'] ?? null,
                'department_id' => Department::firstWhere('name', $row['department_name'] ?? null)->id ?? null,
                'paid_time_off_amount' => $row['paid_time_off_day'] ?? 16,
            ]);

        $user->assignRole($row['role']);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'job_title' => ['required', 'string'],
            'role' => ['required', 'string', Rule::notIn(['System Manager']), new MustBelongToCompany('roles', 'name')],
            'warehouse_name' => ['required', 'string', new MustBelongToCompany('warehouses', 'name')],
            'gender' => ['required', 'string', 'max:255', Rule::in(['male', 'female'])],
            'address' => ['required', 'string', 'max:255'],
            'bank_name' => ['nullable', 'string', 'max:255', 'required_unless:*.bank_account,null'],
            'bank_account' => ['nullable', 'string', 'max:255', 'required_unless:*.bank_name,null'],
            'tin_number' => ['nullable', 'string', 'max:255', 'distinct', Rule::unique('employees')->where('company_id', userCompany()->id)->withoutTrashed()],
            'job_type' => ['required', 'string', 'max:255', Rule::in(['full time', 'part time', 'contractual', 'remote', 'internship'])],
            'phone' => ['required', 'string', 'max:255'],
            'id_type' => ['nullable', 'string', 'max:255', Rule::in(['passport', 'drivers license', 'employee id', 'kebele id', 'student id'])],
            'id_number' => ['nullable', 'string', 'max:255'],
            'date_of_hiring' => ['nullable', 'date'],
            'date_of_birth' => ['nullable', 'date', 'before:' . now()],
            'emergency_name' => ['nullable', 'string', 'max:255', 'required_unless:*.emergency_phone,null'],
            'emergency_phone' => ['nullable', 'string', 'max:255', 'required_unless:*.emergency_name,null'],
            'department_name' => ['nullable', 'string', Rule::when(!isFeatureEnabled('Department Management'), 'prohibited'), new MustBelongToCompany('departments', 'name')],
            'paid_time_off_day' => ['nullable', 'numeric'],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['name'] = str()->squish($data['name'] ?? '');
        $data['email'] = str()->squish($data['email'] ?? '');

        return $data;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
