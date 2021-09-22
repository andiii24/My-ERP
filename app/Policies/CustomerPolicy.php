<?php

namespace App\Policies;

use App\Models\Customer;
use App\Traits\ModelToCompanyBelongingnessChecker;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization, ModelToCompanyBelongingnessChecker;

    public function viewAny(User $user)
    {
        return $user->can('Read Customer');
    }

    public function view(User $user, Customer $customer)
    {
        return $this->doesModelBelongToMyCompany($user, $customer) && $user->can('Read Customer');
    }

    public function create(User $user)
    {
        return $user->can('Create Customer');
    }

    public function update(User $user, Customer $customer)
    {
        return $this->doesModelBelongToMyCompany($user, $customer) && $user->can('Update Customer');

    }

    public function delete(User $user, Customer $customer)
    {
        return $this->doesModelBelongToMyCompany($user, $customer) && $user->can('Delete Customer');
    }
}
