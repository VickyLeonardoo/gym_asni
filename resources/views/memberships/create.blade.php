<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Perpanjang Membership - {{ $member->name }}
        </h2>
    </x-slot>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('memberships.store', $member) }}" enctype="multipart/form-data" class="rounded-md border bg-white p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium">Paket</label>
                <select name="membership_plan_id" data-plan-select class="mt-1 w-full rounded-md border-gray-300" required>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" data-price="{{ $plan->price }}" @selected(old('membership_plan_id') == $plan->id)>{{ $plan->name }} - Rp {{ number_format($plan->price, 0, ',', '.') }} / {{ $plan->duration_days }} hari</option>
                    @endforeach
                </select>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium">Tanggal Mulai</label>
                    <input type="date" name="starts_at" value="{{ old('starts_at', $suggestedStartsAt) }}" class="mt-1 w-full rounded-md border-gray-300" required>
                    <p class="mt-1 text-xs text-gray-500">Default lanjut dari tanggal habis membership aktif.</p>
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium">Jumlah Pembayaran</label>
                    <input type="number" min="0.01" step="0.01" name="amount" data-payment-amount value="{{ old('amount') }}" class="mt-1 w-full rounded-md border-gray-300" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Tanggal Bayar</label>
                    <input type="date" name="paid_at" value="{{ old('paid_at') }}" class="mt-1 w-full rounded-md border-gray-300">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium">Metode Pembayaran</label>
                <input name="method" value="{{ old('method', 'bank_transfer') }}" class="mt-1 w-full rounded-md border-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium">Bukti Pembayaran</label>
                <input type="file" name="proof" class="mt-1 w-full rounded-md border-gray-300 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">Catatan</label>
                <textarea name="notes" class="mt-1 w-full rounded-md border-gray-300">{{ old('notes') }}
                </textarea>
            </div>
            <button class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">Perpanjang Membership</button>
        </form>
    </div>
    <script>
        (() => {
            const plan = document.querySelector('[data-plan-select]');
            const amount = document.querySelector('[data-payment-amount]');
            const syncAmount = () => amount.value = plan.selectedOptions[0]?.dataset.price ?? '';
            plan.addEventListener('change', syncAmount);
            if (!amount.value) syncAmount();
        })();
    </script>
</x-app-layout>
