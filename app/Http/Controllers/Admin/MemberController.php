<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\StoreMemberRequest;
use App\Http\Requests\Member\UpdateMemberRequest;
use App\Models\Member;
use App\Models\MembershipPlan;
use App\Services\MemberService;
use App\Services\MembershipTransactionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(Request $request, MemberService $service): View
    {
        $this->authorize('viewAny', Member::class);

        return view('members.index', [
            'members' => $service->paginate($request->only(['search', 'status'])),
        ]);
    }

    public function archived(Request $request, MemberService $service): View
    {
        $this->authorize('viewAny', Member::class);

        return view('members.archived', [
            'members' => $service->paginateArchived($request->only('search')),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Member::class);

        return view('members.create', [
            'plans' => MembershipPlan::query()->where('is_active', true)->orderBy('duration_days')->get(),
        ]);
    }

    public function store(StoreMemberRequest $request, MembershipTransactionService $service): RedirectResponse
    {
        $transaction = $service->createRegistration($request->validated());

        return redirect()->route('transactions.index')->with('status', "Transaksi pendaftaran #{$transaction->id} berhasil dibuat. Verifikasi untuk membuat data member.");
    }

    public function show(Member $member): View
    {
        $this->authorize('view', $member);

        return view('members.show', [
            'member' => $member->load(['memberships.plan', 'memberships.payments']),
        ]);
    }

    public function edit(Member $member): View
    {
        $this->authorize('update', $member);

        return view('members.edit', ['member' => $member]);
    }

    public function update(UpdateMemberRequest $request, Member $member, MemberService $service): RedirectResponse
    {
        $service->update($member, $request->validated());

        return redirect()->route('members.show', $member)->with('status', 'Member berhasil diperbarui.');
    }

    public function destroy(Member $member, MemberService $service): RedirectResponse
    {
        $this->authorize('delete', $member);
        $service->delete($member);

        return redirect()->route('members.index')->with('status', 'Member berhasil diarsipkan.');
    }

    public function restore(string $member, MemberService $service): RedirectResponse
    {
        $member = Member::withTrashed()->findOrFail($member);
        $this->authorize('restore', $member);
        $service->restore($member);

        return redirect()->route('members.archived')->with('status', 'Member berhasil dipulihkan.');
    }
}
