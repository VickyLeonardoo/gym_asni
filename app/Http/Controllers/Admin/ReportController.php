<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AssetCondition;
use App\Enums\AssetStatus;
use App\Enums\MembershipStatus;
use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetMaintenance;
use App\Models\Member;
use App\Models\Membership;
use App\Models\MembershipPayment;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('reports.index', [
            'memberCount' => Member::query()->count(),
            'activeCount' => Membership::query()->where('status', MembershipStatus::Active)->count(),
            'expiredCount' => Membership::query()->where('status', MembershipStatus::Expired)->count(),
            'revenue' => MembershipPayment::query()->where('status', 'verified')->sum('amount'),
            'assetCount' => Asset::query()->count(),
            'maintenanceCount' => AssetMaintenance::query()->count(),
            'assetsByStatus' => Asset::query()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'assetsByCondition' => Asset::query()->selectRaw('`condition`, count(*) as total')->groupBy('condition')->pluck('total', 'condition'),
            'assetStatuses' => AssetStatus::cases(),
            'assetConditions' => AssetCondition::cases(),
        ]);
    }
}
