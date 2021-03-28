<?php

namespace App\Policies;

use App\Models\Supplier;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupplierPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Supplier');
    }

    public function view(User $user, Supplier $supplier)
    {
        $doesSupplierBelongToMyCompany = $user->employee->company_id == $supplier->company_id;

        return $doesSupplierBelongToMyCompany && $user->can('Read Supplier');
    }

    public function create(User $user)
    {
        return $user->can('Create Supplier');
    }

    public function update(User $user, Supplier $supplier)
    {
        $doesSupplierBelongToMyCompany = $user->employee->company_id == $supplier->company_id;

        return $doesSupplierBelongToMyCompany && $user->can('Update Supplier');
    }

    public function delete(User $user, Supplier $supplier)
    {
        $doesSupplierBelongToMyCompany = $user->employee->company_id == $supplier->company_id;

        return $doesSupplierBelongToMyCompany && $user->can('Delete Supplier');
    }
}
