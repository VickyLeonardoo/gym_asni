<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Services\MembershipService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(DashboardService $dashboardService, MembershipService $membershipService): View
    {
        $membershipService->markExpiredMemberships();

        return view('dashboard', $dashboardService->summary());
    }
}
