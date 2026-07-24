<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Tambah Transaksi</h2>
    </x-slot>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('transactions.store') }}" enctype="multipart/form-data" class="rounded-md border bg-white p-6 space-y-6">
            @csrf

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipe Transaksi</label>
                    <select id="transaction-type" name="type" class="mt-1 w-full rounded-md border-gray-300" required>
                        @foreach($types as $type)
                            <option value="{{ $type->value }}" @selected(old('type', $selectedType) === $type->value)>{{ $type->label() }}</option>
                        @endforeach
                    </select>

                    <x-input-error :messages="$errors->get('type')" class="mt-1" />
                </div>
                <div data-renewal-section>
                    <label class="block text-sm font-medium text-gray-700">Member Terdaftar</label>
                    <select name="member_id" class="mt-1 w-full rounded-md border-gray-300">
                        <option value="">Pilih member</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" @selected((int) old('member_id', $selectedMemberId) === $member->id)>{{ $member->name }} - {{ $member->member_code }} - {{ $member->phone }}</option>
                        @endforeach
                    </select>

                    <x-input-error :messages="$errors->get('member_id')" class="mt-1" />
                </div>
            </div>
            <div data-registration-section class="rounded-md border bg-gray-50 p-4">
                <h3 class="font-semibold text-gray-900">Data Member Baru</h3>
                <p class="mt-1 text-sm text-gray-500">Data ini baru menjadi member setelah transaksi diverifikasi.</p>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium">Kode Member</label>
                        <input name="member_code" value="{{ old('member_code') }}" placeholder="Otomatis jika kosong" class="mt-1 w-full rounded-md border-gray-300">

                        <x-input-error :messages="$errors->get('member_code')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Nama</label>
                        <input name="name" value="{{ old('name') }}" class="mt-1 w-full rounded-md border-gray-300">

                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-md border-gray-300">

                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium">No. HP</label>
                        <input name="phone" value="{{ old('phone') }}" class="mt-1 w-full rounded-md border-gray-300">

                        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Tanggal Lahir</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="mt-1 w-full rounded-md border-gray-300">

                        <x-input-error :messages="$errors->get('date_of_birth')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Gender</label>
                        <select name="gender" class="mt-1 w-full rounded-md border-gray-300">
                            <option value="">Pilih</option>
                            @foreach(['male','female','other'] as $gender)
                                <option value="{{ $gender }}" @selected(old('gender') === $gender)>{{ ucfirst($gender) }}</option>
                            @endforeach
                        </select>

                        <x-input-error :messages="$errors->get('gender')" class="mt-1" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium">Alamat</label>
                        <textarea name="address" class="mt-1 w-full rounded-md border-gray-300">{{ old('address') }}
                        </textarea>

                        <x-input-error :messages="$errors->get('address')" class="mt-1" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium">Kontak Darurat</label>
                        <textarea name="emergency_contact" class="mt-1 w-full rounded-md border-gray-300">{{ old('emergency_contact') }}
                        </textarea>

                        <x-input-error :messages="$errors->get('emergency_contact')" class="mt-1" />
                    </div>
                </div>
            </div>
            <div class="rounded-md border p-4">
                <h3 class="font-semibold text-gray-900">Paket & Pembayaran</h3>
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
                        <input type="date" name="starts_at" value="{{ old('starts_at', $suggestedStartsAt) }}" class="mt-1 w-full rounded-md border-gray-300" required>

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
                        <label class="block text-sm font-medium">Catatan</label>
                        <textarea name="notes" class="mt-1 w-full rounded-md border-gray-300">{{ old('notes') }}
                        </textarea>

                        <x-input-error :messages="$errors->get('notes')" class="mt-1" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium">Catatan Pembayaran</label>
                        <textarea name="payment_notes" class="mt-1 w-full rounded-md border-gray-300">{{ old('payment_notes') }}
                        </textarea>

                        <x-input-error :messages="$errors->get('payment_notes')" class="mt-1" />
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">Buat Transaksi</button>
                <a href="{{ route('transactions.index') }}" class="text-sm text-gray-600">Batal</a>
            </div>
        </form>
    </div>
    <script>
        (() => {
        const type = document.getElementById('transaction-type');
        const registrationSections = document.querySelectorAll('[data-registration-section]');
        const renewalSections = document.querySelectorAll('[data-renewal-section]');

        const sync = () => {
        const isRenewal = type.value === 'renewal';
        registrationSections.forEach((section) => section.hidden = isRenewal);
        renewalSections.forEach((section) => section.hidden = !isRenewal);
        };

        type.addEventListener('change', sync);
        sync();

        const plan = document.querySelector('[data-plan-select]');
        const amount = document.querySelector('[data-payment-amount]');
        const syncAmount = () => amount.value = plan.selectedOptions[0]?.dataset.price ?? '';
        plan.addEventListener('change', syncAmount);
        if (!amount.value) syncAmount();
        })();
    </script>
</x-app-layout>
