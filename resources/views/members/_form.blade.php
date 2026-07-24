@csrf
<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="block text-sm font-medium text-gray-700">Kode Member</label>
        <input name="member_code" value="{{ old('member_code', $member->member_code ?? '') }}" placeholder="Otomatis jika kosong" class="mt-1 w-full rounded-md border-gray-300">

        <x-input-error :messages="$errors->get('member_code')" class="mt-1" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Nama</label>
        <input name="name" value="{{ old('name', $member->name ?? '') }}" class="mt-1 w-full rounded-md border-gray-300" required>

        <x-input-error :messages="$errors->get('name')" class="mt-1" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" value="{{ old('email', $member->email ?? '') }}" class="mt-1 w-full rounded-md border-gray-300">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">No. HP</label>
        <input name="phone" value="{{ old('phone', $member->phone ?? '') }}" class="mt-1 w-full rounded-md border-gray-300" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', isset($member) && $member->date_of_birth ? $member->date_of_birth->format('Y-m-d') : '') }}" class="mt-1 w-full rounded-md border-gray-300">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Gender</label>
        <select name="gender" class="mt-1 w-full rounded-md border-gray-300">
            <option value="">Pilih</option>
            @foreach(['male','female','other'] as $gender)
                <option value="{{ $gender }}" @selected(old('gender', $member->gender ?? '') === $gender)>{{ ucfirst($gender) }}</option>
            @endforeach
        </select>
    </div>
    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Alamat</label>
        <textarea name="address" class="mt-1 w-full rounded-md border-gray-300">{{ old('address', $member->address ?? '') }}
        </textarea>
    </div>
    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Kontak Darurat</label>
        <textarea name="emergency_contact" class="mt-1 w-full rounded-md border-gray-300">{{ old('emergency_contact', $member->emergency_contact ?? '') }}
        </textarea>
    </div>
</div>
@isset($plans)
    <div class="mt-6 border-t pt-6">
        <h3 class="font-semibold text-gray-900">Transaksi Awal</h3>
        <p class="mt-1 text-sm text-gray-500">Data member baru dibuat setelah transaksi ini diverifikasi.</p>
        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium">Paket</label>
                <select name="membership_plan_id" data-plan-select class="mt-1 w-full rounded-md border-gray-300" required>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" data-price="{{ $plan->price }}" @selected(old('membership_plan_id') == $plan->id)>{{ $plan->name }} - Rp {{ number_format($plan->price, 0, ',', '.') }} / {{ $plan->duration_days }} hari</option>
                    @endforeach
                </select>

                <x-input-error :messages="$errors->get('membership_plan_id')" class="mt-1" />
            </div>
            <div>
                <label class="block text-sm font-medium">Tanggal Mulai</label>
                <input type="date" name="starts_at" value="{{ old('starts_at', now()->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border-gray-300" required>

                <x-input-error :messages="$errors->get('starts_at')" class="mt-1" />
            </div>
            <div>
                <label class="block text-sm font-medium">Jumlah Pembayaran</label>
                <input type="number" min="0.01" step="0.01" name="amount" data-payment-amount value="{{ old('amount') }}" class="mt-1 w-full rounded-md border-gray-300" required>

                <x-input-error :messages="$errors->get('amount')" class="mt-1" />
            </div>
            <div>
                <label class="block text-sm font-medium">Tanggal Bayar</label>
                <input type="date" name="paid_at" value="{{ old('paid_at') }}" class="mt-1 w-full rounded-md border-gray-300">

                <x-input-error :messages="$errors->get('paid_at')" class="mt-1" />
            </div>
            <div>
                <label class="block text-sm font-medium">Metode Pembayaran</label>
                <input name="method" value="{{ old('method', 'bank_transfer') }}" class="mt-1 w-full rounded-md border-gray-300">

                <x-input-error :messages="$errors->get('method')" class="mt-1" />
            </div>
            <div>
                <label class="block text-sm font-medium">Bukti Pembayaran</label>
                <input type="file" name="proof" class="mt-1 w-full rounded-md border-gray-300 text-sm">

                <x-input-error :messages="$errors->get('proof')" class="mt-1" />
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium">Catatan Pembayaran</label>
                <textarea name="payment_notes" class="mt-1 w-full rounded-md border-gray-300">{{ old('payment_notes') }}
                </textarea>

                <x-input-error :messages="$errors->get('payment_notes')" class="mt-1" />
            </div>
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
    </div>
@endisset

<div class="mt-6 flex items-center gap-3">
    <button class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">{{ isset($plans) ? 'Buat Transaksi' : 'Simpan Member' }}
    </button>
    <a href="{{ $cancelRoute ?? route('members.index') }}" class="text-sm text-gray-600">Batal</a>
</div>
