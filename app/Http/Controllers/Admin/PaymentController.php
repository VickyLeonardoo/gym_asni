<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\UploadPaymentProofRequest;
use App\Models\Membership;
use App\Models\MembershipPayment;
use App\Services\MembershipService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', MembershipPayment::class);

        return view('payments.index', [
            'payments' => MembershipPayment::query()
                ->with(['membership.member', 'membership.plan', 'uploader'])
                ->when($request->string('status')->toString(), fn ($query, string $status) => $query->where('status', $status))
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'statuses' => PaymentStatus::cases(),
        ]);
    }

    public function edit(Membership $membership): View
    {
        $this->authorize('create', MembershipPayment::class);

        return view('payments.edit', ['membership' => $membership->load(['member', 'plan', 'payments'])]);
    }

    public function update(UploadPaymentProofRequest $request, Membership $membership, MembershipService $service): RedirectResponse
    {
        $service->uploadProof($membership, $request->validated());

        return redirect()->route('memberships.show', $membership)->with('status', 'Payment proof uploaded successfully.');
    }

    public function verify(MembershipPayment $payment, MembershipService $service): RedirectResponse
    {
        $this->authorize('update', $payment);
        $status = request()->boolean('reject') ? PaymentStatus::Rejected : PaymentStatus::Verified;
        $service->verifyPayment($payment, $status);

        return back()->with('status', 'Payment status updated successfully.');
    }
}
