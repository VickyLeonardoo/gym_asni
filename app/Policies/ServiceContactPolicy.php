<?php

namespace App\Policies;

use App\Models\ServiceContact;
use App\Models\User;

class ServiceContactPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active;
    }

    public function create(User $user): bool
    {
        return $user->is_active;
    }

    public function update(User $user, ServiceContact $serviceContact): bool
    {
        return $user->is_active;
    }

    public function delete(User $user, ServiceContact $serviceContact): bool
    {
        return $user->is_active;
    }
}
