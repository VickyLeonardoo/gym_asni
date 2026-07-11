<?php

namespace App\Policies;

use App\Models\MembershipPayment;
use App\Models\User;

class MembershipPaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active;
    }

    public function create(User $user): bool
    {
        return $user->is_active;
    }

    public function update(User $user, MembershipPayment $membershipPayment): bool
    {
        return $user->is_active;
    }
}
