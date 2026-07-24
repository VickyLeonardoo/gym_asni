<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Detail Membership</h2>
    </x-slot>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="rounded-md border bg-white p-6 grid gap-4 sm:grid-cols-3 text-sm">
            <div>
                <span class="text-gray-500">Member</span>
                <p class="font-medium">{{ $membership->member->name }}</p>
            </div>
            <div>
                <span class="text-gray-500">Paket</span>
                <p class="font-medium">{{ $membership->plan->name }}</p>
            </div>
            <div>
                <span class="text-gray-500">Status</span>
                <p class="font-medium">{{ $membership->status->label() }}</p>
            </div>
            <div>
                <span class="text-gray-500">Mulai</span>
                <p>{{ $membership->starts_at->format('d M Y') }}</p>
            </div>
            <div>
                <span class="text-gray-500">Berakhir</span>
                <p>{{ $membership->expires_at->format('d M Y') }}</p>
            </div>
            <div>
                <span class="text-gray-500">Harga</span>
                <p>Rp {{ number_format($membership->price, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="rounded-md border bg-white overflow-hidden">
            <div class="px-4 py-3 font-semibold">Pembayaran</div>
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left">
                    <tr>
                        <th class="px-4 py-3">Jumlah</th>
                        <th>Tanggal Bayar</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Bukti</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($membership->payments as $payment)
                        <tr>
                            <td class="px-4 py-3">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>{{ $payment->paid_at?->format('d M Y') ?: '-' }}</td>
                            <td>{{ $payment->method }}</td>
                            <td>{{ $payment->status->label() }}</td>
                            <td>
                                @if($payment->proof_path)
                                    <a target="_blank" href="{{ Storage::url($payment->proof_path) }}" class="text-gray-900">Buka</a>
                                @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-app-layout>
