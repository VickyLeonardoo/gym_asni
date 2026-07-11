<?php

namespace App\Http\Requests\Membership;

use App\Models\Membership;
use Illuminate\Foundation\Http\FormRequest;

class RenewMembershipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Membership::class);
    }

    public function rules(): array
    {
        return [
            'membership_plan_id' => ['required', 'exists:membership_plans,id'],
            'starts_at' => ['required', 'date'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'paid_at' => ['nullable', 'date'],
            'method' => ['nullable', 'string', 'max:100'],
            'proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'payment_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
