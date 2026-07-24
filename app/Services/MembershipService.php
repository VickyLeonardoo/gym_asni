<?php

namespace App\Services;

use App\Enums\MembershipStatus;
use App\Enums\PaymentStatus;
use App\Models\Member;
use App\Models\Membership;
use App\Models\MembershipPayment;
use App\Models\MembershipPlan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MembershipService
{
    public function paginate(array $filters): LengthAwarePaginator
    {
        $this->markExpiredMemberships();

        return Membership::query()
            ->with(['member', 'plan', 'payments'])
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->whereHas('member', fn ($query) => $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('member_code', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%"));
            })
            ->when($filters['status'] ?? null, fn ($query, string $status) => $query->where('status', $status))
            ->latest('expires_at')
            ->paginate(15)
            ->withQueryString();
    }

    public function renew(Member $member, array $data): Membership
    {
        return DB::transaction(function () use ($member, $data): Membership {
            $plan = MembershipPlan::query()->findOrFail($data['membership_plan_id']);
            $latestActiveMembership = $member->memberships()
                ->where('status', MembershipStatus::Active->value)
                ->whereDate('expires_at', '>=', now()->toDateString())
                ->latest('expires_at')
                ->first();

            $defaultStartsAt = $latestActiveMembership
                ? $latestActiveMembership->expires_at->copy()->addDay()
                : now();

            $startsAt = Carbon::parse($data['starts_at'] ?? $defaultStartsAt->toDateString());
            $expiresAt = $startsAt->copy()->addDays($plan->duration_days - 1);

            $member->memberships()
                ->where('status', MembershipStatus::Active->value)
                ->whereDate('expires_at', '>=', $startsAt->toDateString())
                ->update(['status' => MembershipStatus::Expired->value]);

            $membership = Membership::query()->create([
                'member_id' => $member->id,
                'membership_plan_id' => $plan->id,
                'starts_at' => $startsAt,
                'expires_at' => $expiresAt,
                'price' => $plan->price,
                'status' => MembershipStatus::Active->value,
                'notes' => $data['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            MembershipPayment::query()->create([
                'membership_id' => $membership->id,
                'amount' => $data['amount'],
                'paid_at' => $data['paid_at'] ?? null,
                'method' => $data['method'] ?? 'bank_transfer',
                'status' => PaymentStatus::Pending->value,
                'notes' => $data['payment_notes'] ?? null,
                'uploaded_by' => auth()->id(),
                'proof_path' => isset($data['proof']) && $data['proof'] instanceof UploadedFile
                    ? $data['proof']->store('payment-proofs', 'public')
                    : null,
            ]);

            return $membership->load(['member', 'plan', 'payments']);
        });
    }

    public function markExpiredMemberships(): int
    {
        return Membership::query()
            ->where('status', MembershipStatus::Active->value)
            ->whereDate('expires_at', '<', now()->toDateString())
            ->update(['status' => MembershipStatus::Expired->value]);
    }
}
