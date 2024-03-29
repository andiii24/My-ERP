<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;
use App\Traits\VerifyModelIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReservationPolicy
{
    use HandlesAuthorization, VerifyModelIssuer;

    public function viewAny(User $user)
    {
        return $user->can('Read Reservation');
    }

    public function view(User $user, Reservation $reservation)
    {
        return $user->can('Read Reservation');
    }

    public function create(User $user)
    {
        return $user->can('Create Reservation');
    }

    public function update(User $user, Reservation $reservation)
    {
        return $this->isIssuedByMyBranch($user, $reservation) && $user->can('Update Reservation');
    }

    public function delete(User $user, Reservation $reservation)
    {
        return $this->isIssuedByMyBranch($user, $reservation) && $user->can('Delete Reservation');
    }

    public function approve(User $user, Reservation $reservation)
    {
        return $user->can('Approve Reservation');
    }

    public function reserve(User $user, Reservation $reservation)
    {
        return $user->can('Make Reservation');
    }

    public function cancel(User $user, Reservation $reservation)
    {
        return $user->can('Cancel Reservation');
    }

    public function convert(User $user, Reservation $reservation)
    {
        return $user->can('Convert Reservation');
    }
}
