<?php

namespace App\Services;

use App\Enums\MembershipStatus;
use App\Enums\MembershipTransactionType;
use App\Enums\PaymentStatus;
use App\Models\Member;
use App\Models\Membership;
use App\Models\MembershipPayment;
use App\Models\MembershipPlan;
use App\Models\MembershipTransaction;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MembershipTransactionService
{
    public function createRegistration(array $data): MembershipTransaction
    {
        return DB::transaction(function () use ($data): MembershipTransaction {
            $plan = MembershipPlan::query()->findOrFail($data['membership_plan_id']);
            $startsAt = Carbon::parse($data['starts_at'] ?? now()->toDateString());

            return MembershipTransaction::query()->create([
                ...$this->memberData($data),
                ...$this->paymentData($data, $plan, $startsAt),
                'type' => MembershipTransactionType::Registration->value,
                'created_by' => auth()->id(),
            ]);
        });
    }

    public function createRenewal(Member $member, array $data): MembershipTransaction
    {
        return DB::transaction(function () use ($member, $data): MembershipTransaction {
            $plan = MembershipPlan::query()->findOrFail($data['membership_plan_id']);
            $startsAt = Carbon::parse($data['starts_at'] ?? now()->toDateString());

            return MembershipTransaction::query()->create([
                ...$this->paymentData($data, $plan, $startsAt),
                'type' => MembershipTransactionType::Renewal->value,
                'member_id' => $member->id,
                'name' => $member->name,
                'email' => $member->email,
                'phone' => $member->phone,
                'created_by' => auth()->id(),
            ]);
        });
    }

    public function verify(MembershipTransaction $transaction, PaymentStatus $status): MembershipTransaction
    {
        return DB::transaction(function () use ($transaction, $status): MembershipTransaction {
            $transaction->update([
                'status' => $status->value,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            if ($status === PaymentStatus::Verified && ! $transaction->finalized_membership_id) {
                $membership = $this->finalize($transaction->refresh());

                $transaction->update(['finalized_membership_id' => $membership->id]);
            }

            return $transaction->refresh();
        });
    }

    private function finalize(MembershipTransaction $transaction): Membership
    {
        $member = $transaction->member;

        if (! $member) {
            $member = Member::query()->create([
                'member_code' => $transaction->member_code ?: $this->nextMemberCode(),
                'name' => $transaction->name,
                'email' => $transaction->email,
                'phone' => $transaction->phone,
                'date_of_birth' => $transaction->date_of_birth,
                'gender' => $transaction->gender,
                'address' => $transaction->address,
                'emergency_contact' => $transaction->emergency_contact,
                'created_by' => $transaction->created_by,
            ]);
        }

        $plan = $transaction->plan;
        $startsAt = $transaction->starts_at;
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
            'price' => $transaction->price,
            'status' => MembershipStatus::Active->value,
            'notes' => $transaction->notes,
            'created_by' => $transaction->created_by,
        ]);

        MembershipPayment::query()->create([
            'membership_id' => $membership->id,
            'amount' => $transaction->amount,
            'paid_at' => $transaction->paid_at,
            'method' => $transaction->method,
            'proof_path' => $transaction->proof_path,
            'status' => PaymentStatus::Verified->value,
            'notes' => $transaction->payment_notes,
            'uploaded_by' => $transaction->created_by,
            'verified_by' => $transaction->verified_by,
            'verified_at' => $transaction->verified_at,
        ]);

        return $membership;
    }

    private function memberData(array $data): array
    {
        return [
            'member_code' => $data['member_code'] ?? null,
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'],
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => $data['gender'] ?? null,
            'address' => $data['address'] ?? null,
            'emergency_contact' => $data['emergency_contact'] ?? null,
        ];
    }

    private function paymentData(array $data, MembershipPlan $plan, Carbon $startsAt): array
    {
        return [
            'membership_plan_id' => $plan->id,
            'starts_at' => $startsAt,
            'price' => $data['price'] ?? $plan->price,
            'amount' => $data['amount'] ?? ($data['price'] ?? $plan->price),
            'paid_at' => $data['paid_at'] ?? null,
            'method' => $data['method'] ?? 'bank_transfer',
            'proof_path' => isset($data['proof']) && $data['proof'] instanceof UploadedFile
                ? $data['proof']->store('payment-proofs', 'public')
                : null,
            'status' => PaymentStatus::Pending->value,
            'notes' => $data['notes'] ?? null,
            'payment_notes' => $data['payment_notes'] ?? null,
        ];
    }

    private function nextMemberCode(): string
    {
        $nextId = (int) (Member::withTrashed()->max('id') ?? 0) + 1;

        return 'MBR-'.str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);
    }
}
