<?php

namespace App\Policies;

use App\Models\Asset;
use App\Models\User;

class AssetPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active;
    }

    public function view(User $user, Asset $asset): bool
    {
        return $user->is_active;
    }

    public function create(User $user): bool
    {
        return $user->is_active;
    }

    public function update(User $user, Asset $asset): bool
    {
        return $user->is_active;
    }

    public function delete(User $user, Asset $asset): bool
    {
        return $user->is_active;
    }

    public function restore(User $user, Asset $asset): bool
    {
        return $user->is_active;
    }
}
