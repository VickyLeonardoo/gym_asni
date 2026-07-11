<?php

namespace App\Policies;

use App\Models\AssetMaintenance;
use App\Models\User;

class AssetMaintenancePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active;
    }

    public function create(User $user): bool
    {
        return $user->is_active;
    }

    public function update(User $user, AssetMaintenance $assetMaintenance): bool
    {
        return $user->is_active;
    }

    public function delete(User $user, AssetMaintenance $assetMaintenance): bool
    {
        return $user->is_active;
    }
}
