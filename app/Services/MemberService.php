<?php

namespace App\Services;

use App\Enums\MembershipStatus;
use App\Models\Member;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class MemberService
{
    public function paginate(array $filters): LengthAwarePaginator
    {
        app(MembershipService::class)->markExpiredMemberships();

        return Member::query()
            ->with(['latestMembership.plan', 'latestMembership.payments'])
            ->search($filters['search'] ?? null)
            ->when(($filters['status'] ?? null) === 'expired', function ($query): void {
                $query
                    ->whereHas('memberships')
                    ->whereDoesntHave('memberships', fn ($query) => $query
                        ->where('status', MembershipStatus::Active->value)
                        ->whereDate('expires_at', '>=', now()->toDateString()));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();
    }

    public function create(array $data): Member
    {
        return DB::transaction(function () use ($data): Member {
            $data['member_code'] = $data['member_code'] ?? $this->nextMemberCode();
            $data['created_by'] = auth()->id();

            return Member::query()->create($data);
        });
    }

    public function update(Member $member, array $data): Member
    {
        return DB::transaction(function () use ($member, $data): Member {
            $data['updated_by'] = auth()->id();
            $member->update($data);

            return $member->refresh();
        });
    }

    public function delete(Member $member): void
    {
        DB::transaction(fn () => $member->delete());
    }

    private function nextMemberCode(): string
    {
        $nextId = (int) (Member::withTrashed()->max('id') ?? 0) + 1;

        return 'MBR-'.str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);
    }
}
