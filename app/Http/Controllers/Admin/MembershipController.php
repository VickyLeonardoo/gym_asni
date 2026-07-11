<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MembershipStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Membership\RenewMembershipRequest;
use App\Models\Member;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Services\MembershipService;
use App\Services\MembershipTransactionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MembershipController extends Controller
{
    public function index(Request $request, MembershipService $service): View
    {
        $this->authorize('viewAny', Membership::class);

        return view('memberships.index', [
            'memberships' => $service->paginate($request->only(['search', 'status'])),
            'statuses' => MembershipStatus::cases(),
        ]);
    }

    public function create(Member $member): View
    {
        $this->authorize('create', Membership::class);

        $latestActiveMembership = $member->memberships()
            ->where('status', MembershipStatus::Active->value)
            ->whereDate('expires_at', '>=', now()->toDateString())
            ->latest('expires_at')
            ->first();

        return view('memberships.create', [
            'member' => $member,
            'plans' => MembershipPlan::query()->where('is_active', true)->orderBy('duration_days')->get(),
            'suggestedStartsAt' => $latestActiveMembership
                ? $latestActiveMembership->expires_at->copy()->addDay()->format('Y-m-d')
                : now()->format('Y-m-d'),
        ]);
    }

    public function store(RenewMembershipRequest $request, Member $member, MembershipTransactionService $service): RedirectResponse
    {
        $transaction = $service->createRenewal($member, $request->validated());

        return redirect()->route('transactions.index')->with('status', "Renewal transaction #{$transaction->id} created. Verify it to finalize renewal.");
    }

    public function show(Membership $membership): View
    {
        $this->authorize('view', $membership);

        return view('memberships.show', [
            'membership' => $membership->load(['member', 'plan', 'payments.uploader']),
        ]);
    }
}
