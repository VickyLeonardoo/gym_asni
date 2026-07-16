<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\User;

class MemberPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active;
    }

    public function view(User $user, Member $member): bool
    {
        return $user->is_active;
    }

    public function create(User $user): bool
    {
        return $user->is_active;
    }

    public function update(User $user, Member $member): bool
    {
        return $user->is_active;
    }

    public function delete(User $user, Member $member): bool
    {
        return $user->is_active;
    }

    public function restore(User $user, Member $member): bool
    {
        return $user->is_active;
    }
}
