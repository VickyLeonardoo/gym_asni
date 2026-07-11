<?php

namespace App\Services;

use App\Enums\AssetCondition;
use App\Enums\AssetStatus;
use App\Enums\MembershipStatus;
use App\Models\Asset;
use App\Models\AssetMaintenance;
use App\Models\Member;
use App\Models\Membership;
use App\Models\MembershipPayment;

class DashboardService
{
    public function summary(): array
    {
        return [
            'total_members' => Member::query()->count(),
            'active_memberships' => Membership::query()->where('status', MembershipStatus::Active->value)->count(),
            'expired_memberships' => Membership::query()->where('status', MembershipStatus::Expired->value)->count(),
            'pending_payments' => MembershipPayment::query()->where('status', 'pending')->count(),
            'total_assets' => Asset::query()->count(),
            'maintenance_assets' => Asset::query()->where('status', AssetStatus::Maintenance->value)->count(),
            'poor_assets' => Asset::query()->whereIn('condition', [AssetCondition::Poor->value, AssetCondition::Broken->value])->count(),
            'upcoming_maintenances' => AssetMaintenance::query()->whereDate('scheduled_at', '>=', now())->count(),
            'recent_members' => Member::query()->latest()->limit(5)->get(),
            'expiring_memberships' => Membership::query()->with('member')->where('status', MembershipStatus::Active->value)->whereBetween('expires_at', [now(), now()->addDays(7)])->oldest('expires_at')->limit(8)->get(),
            'assets_needing_attention' => Asset::query()->whereIn('condition', [AssetCondition::Poor->value, AssetCondition::Broken->value])->orWhere('status', AssetStatus::Maintenance->value)->latest()->limit(8)->get(),
        ];
    }
}
