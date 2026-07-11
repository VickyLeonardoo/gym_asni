<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MembershipTransactionType;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\MembershipTransaction\StoreMembershipTransactionRequest;
use App\Models\Member;
use App\Models\MembershipPlan;
use App\Models\MembershipTransaction;
use App\Services\MembershipTransactionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MembershipTransactionController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', MembershipTransaction::class);

        return view('transactions.index', [
            'transactions' => MembershipTransaction::query()
                ->with(['member', 'plan', 'finalizedMembership.member'])
                ->when($request->string('status')->toString(), fn ($query, string $status) => $query->where('status', $status))
                ->when($request->string('type')->toString(), fn ($query, string $type) => $query->where('type', $type))
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'statuses' => PaymentStatus::cases(),
            'types' => MembershipTransactionType::cases(),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', MembershipTransaction::class);

        $selectedMember = $request->integer('member_id')
            ? Member::query()->find($request->integer('member_id'))
            : null;

        return view('transactions.create', [
            'types' => MembershipTransactionType::cases(),
            'plans' => MembershipPlan::query()->where('is_active', true)->orderBy('duration_days')->get(),
            'members' => Member::query()->orderBy('name')->get(['id', 'member_code', 'name', 'phone']),
            'selectedType' => $request->string('type')->toString() ?: ($selectedMember ? MembershipTransactionType::Renewal->value : MembershipTransactionType::Registration->value),
            'selectedMemberId' => $selectedMember?->id,
            'suggestedStartsAt' => $this->suggestedStartsAt($selectedMember),
        ]);
    }

    public function store(StoreMembershipTransactionRequest $request, MembershipTransactionService $service): RedirectResponse
    {
        $data = $request->validated();

        $transaction = $data['type'] === MembershipTransactionType::Renewal->value
            ? $service->createRenewal(Member::query()->findOrFail($data['member_id']), $data)
            : $service->createRegistration($data);

        return redirect()->route('transactions.index')->with('status', "Transaction #{$transaction->id} created.");
    }

    public function verify(MembershipTransaction $transaction, MembershipTransactionService $service): RedirectResponse
    {
        $this->authorize('update', $transaction);

        $status = request()->boolean('reject') ? PaymentStatus::Rejected : PaymentStatus::Verified;
        $service->verify($transaction, $status);

        return back()->with('status', 'Transaction status updated successfully.');
    }

    private function suggestedStartsAt(?Member $member): string
    {
        $latestActiveMembership = $member?->memberships()
            ->where('status', \App\Enums\MembershipStatus::Active->value)
            ->whereDate('expires_at', '>=', now()->toDateString())
            ->latest('expires_at')
            ->first();

        return $latestActiveMembership
            ? $latestActiveMembership->expires_at->copy()->addDay()->format('Y-m-d')
            : now()->format('Y-m-d');
    }
}
