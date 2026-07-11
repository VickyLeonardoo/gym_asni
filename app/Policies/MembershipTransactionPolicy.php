<?php

namespace App\Policies;

use App\Models\MembershipTransaction;
use App\Models\User;

class MembershipTransactionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active;
    }

    public function view(User $user, MembershipTransaction $membershipTransaction): bool
    {
        return $user->is_active;
    }

    public function create(User $user): bool
    {
        return $user->is_active;
    }

    public function update(User $user, MembershipTransaction $membershipTransaction): bool
    {
        return $user->is_active;
    }
}
