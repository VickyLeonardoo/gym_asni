<?php

namespace App\Http\Requests\MembershipPlan;

use App\Models\MembershipPlan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMembershipPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        $plan = $this->route('package');

        return $plan instanceof MembershipPlan
            && ($this->user()?->can('update', $plan) ?? false);
    }

    public function rules(): array
    {
        $plan = $this->route('package');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('membership_plans', 'name')->ignore($plan)],
            'duration_days' => ['required', 'integer', 'min:1', 'max:3650'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
