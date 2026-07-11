<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MembershipPlan\StoreMembershipPlanRequest;
use App\Http\Requests\MembershipPlan\UpdateMembershipPlanRequest;
use App\Models\MembershipPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MembershipPlanController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', MembershipPlan::class);

        $packages = MembershipPlan::query()
            ->withCount('memberships')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $query->where('name', 'like', '%'.$request->string('search')->toString().'%');
            })
            ->when($request->filled('status'), function ($query) use ($request): void {
                $query->where('is_active', $request->string('status')->toString() === 'active');
            })
            ->orderBy('duration_days')
            ->paginate(10)
            ->withQueryString();

        return view('packages.index', compact('packages'));
    }

    public function create(): View
    {
        $this->authorize('create', MembershipPlan::class);

        return view('packages.create', [
            'package' => new MembershipPlan(['is_active' => true]),
        ]);
    }

    public function store(StoreMembershipPlanRequest $request): RedirectResponse
    {
        MembershipPlan::query()->create($this->data($request->validated()));

        return redirect()->route('packages.index')->with('status', 'Package created successfully.');
    }

    public function edit(MembershipPlan $package): View
    {
        $this->authorize('update', $package);

        return view('packages.edit', compact('package'));
    }

    public function update(UpdateMembershipPlanRequest $request, MembershipPlan $package): RedirectResponse
    {
        $package->update($this->data($request->validated()));

        return redirect()->route('packages.index')->with('status', 'Package updated successfully.');
    }

    public function destroy(MembershipPlan $package): RedirectResponse
    {
        $this->authorize('delete', $package);

        if ($package->memberships()->exists()) {
            return back()->with('status', 'Package already has memberships. Set it inactive instead of deleting it.');
        }

        $package->delete();

        return redirect()->route('packages.index')->with('status', 'Package deleted successfully.');
    }

    private function data(array $validated): array
    {
        return [
            ...$validated,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ];
    }
}
