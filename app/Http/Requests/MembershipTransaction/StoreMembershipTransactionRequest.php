<?php

namespace App\Http\Requests\MembershipTransaction;

use App\Enums\MembershipTransactionType;
use App\Http\Requests\Concerns\ValidatesMembershipPayment;
use App\Models\MembershipTransaction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMembershipTransactionRequest extends FormRequest
{
    use ValidatesMembershipPayment;

    public function authorize(): bool
    {
        return $this->user()?->can('create', MembershipTransaction::class) ?? false;
    }

    public function rules(): array
    {
        $isRegistration = $this->input('type') === MembershipTransactionType::Registration->value;
        $isRenewal = $this->input('type') === MembershipTransactionType::Renewal->value;

        return [
            'type' => ['required', Rule::in(array_map(fn (MembershipTransactionType $type) => $type->value, MembershipTransactionType::cases()))],
            'member_id' => [Rule::requiredIf($isRenewal), 'nullable', 'exists:members,id'],
            'member_code' => ['nullable', 'string', 'max:50', 'unique:members,member_code'],
            'name' => [Rule::requiredIf($isRegistration), 'nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => [Rule::requiredIf($isRegistration), 'nullable', 'string', 'max:30'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'address' => ['nullable', 'string', 'max:1000'],
            'emergency_contact' => ['nullable', 'string', 'max:1000'],
            'membership_plan_id' => ['required', 'exists:membership_plans,id'],
            'starts_at' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'paid_at' => ['nullable', 'date'],
            'method' => ['nullable', 'string', 'max:100'],
            'proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'payment_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
