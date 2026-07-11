<?php

namespace App\Models;

use App\Enums\MembershipTransactionType;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'type',
    'member_id',
    'membership_plan_id',
    'finalized_membership_id',
    'member_code',
    'name',
    'email',
    'phone',
    'date_of_birth',
    'gender',
    'address',
    'emergency_contact',
    'starts_at',
    'price',
    'amount',
    'paid_at',
    'method',
    'proof_path',
    'status',
    'notes',
    'payment_notes',
    'created_by',
    'verified_by',
    'verified_at',
])]
class MembershipTransaction extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'type' => MembershipTransactionType::class,
            'date_of_birth' => 'date',
            'starts_at' => 'date',
            'price' => 'decimal:2',
            'amount' => 'decimal:2',
            'paid_at' => 'date',
            'status' => PaymentStatus::class,
            'verified_at' => 'datetime',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(MembershipPlan::class, 'membership_plan_id');
    }

    public function finalizedMembership(): BelongsTo
    {
        return $this->belongsTo(Membership::class, 'finalized_membership_id');
    }
}
