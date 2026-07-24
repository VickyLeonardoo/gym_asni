<?php

namespace App\Http\Requests\Concerns;

use App\Models\MembershipPlan;
use Illuminate\Validation\Validator;

trait ValidatesMembershipPayment
{
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->hasAny(['membership_plan_id', 'amount'])) {
                return;
            }

            $plan = MembershipPlan::query()->find($this->input('membership_plan_id'));

            if ($plan && round((float) $this->input('amount'), 2) !== round((float) $plan->price, 2)) {
                $validator->errors()->add(
                    'amount',
                    'Jumlah pembayaran harus sama dengan biaya paket, yaitu Rp '.number_format((float) $plan->price, 0, ',', '.').'.'
                );
            }
        });
    }
}
