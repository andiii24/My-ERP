<?php

namespace App\Policies;

use App\Models\Returnn;
use App\Models\User;
use App\Traits\VerifyModelIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReturnPolicy
{
    use HandlesAuthorization, VerifyModelIssuer;

    public function viewAny(User $user)
    {
        return $user->can('Read Return');
    }

    public function view(User $user, Returnn $returnn)
    {
        return $user->can('Read Return');
    }

    public function create(User $user)
    {
        return $user->can('Create Return');
    }

    public function update(User $user, Returnn $returnn)
    {
        return $this->isIssuedByMyBranch($user, $returnn) && $user->can('Update Return');
    }

    public function delete(User $user, Returnn $returnn)
    {
        return $this->isIssuedByMyBranch($user, $returnn) && $user->can('Delete Return');
    }

    public function approve(User $user, Returnn $returnn)
    {
        return $user->can('Approve Return');
    }

    public function add(User $user, Returnn $returnn)
    {
        return $user->can('Make Return');
    }
}
