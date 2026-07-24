<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Transaksi</h2>
            <a href="{{ route('transactions.create') }}" class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">
                <i class="bi bi-plus-lg"></i> Tambah Transaksi
            </a>
        </div>
    </x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('status') }}</div>
        @endif
        <form method="GET" class="mb-4 flex flex-wrap gap-2">
            <select name="type" class="rounded-md border-gray-300 text-sm">
                <option value="">Semua tipe</option>
                @foreach($types as $type)
                    <option value="{{ $type->value }}" @selected(request('type') === $type->value)>{{ $type->label() }}</option>
                @endforeach
            </select>
            <select name="status" class="rounded-md border-gray-300 text-sm">
                <option value="">Semua status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
            <button class="rounded-md border px-3 text-sm">
                <i class="bi bi-filter"></i>
            </button>
        </form>
        <div class="overflow-hidden rounded-md border bg-white">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left">
                    <tr>
                        <th class="px-4 py-3">Transaksi</th>
                        <th>Member</th>
                        <th>Paket</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Bukti</th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $transaction)
                        <tr>
                            <td class="px-4 py-3">
                                <p class="font-medium">#{{ $transaction->id }} {{ $transaction->type->label() }}</p>
                                <p class="text-xs text-gray-500">{{ $transaction->created_at->format('d M Y H:i') }}</p>
                            </td>
                            <td>
                                <p class="font-medium">{{ $transaction->member?->name ?? $transaction->name }}</p>
                                <p class="text-xs text-gray-500">{{ $transaction->phone ?: '-' }}</p>
                            </td>
                            <td>{{ $transaction->plan->name }}
                                <p class="text-xs text-gray-500">Mulai {{ $transaction->starts_at->format('d M Y') }}</p>
                            </td>
                            <td>Rp {{ number_format((float) $transaction->amount, 0, ',', '.') }}</td>
                            <td>{{ $transaction->status->label() }}</td>
                            <td>
                                @if($transaction->proof_path)
                                    <a href="{{ Storage::url($transaction->proof_path) }}" target="_blank">Buka</a>
                                @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 text-right">
                                    @if($transaction->status === \App\Enums\PaymentStatus::Pending)
                                        <form method="POST" action="{{ route('transactions.verify', $transaction) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button class="text-emerald-700">Verifikasi</button>
                                        </form>
                                        <form method="POST" action="{{ route('transactions.verify', $transaction) }}" class="inline ms-3">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="reject" value="1">
                                            <button class="text-red-700">Tolak</button>
                                        </form>
                                    @elseif($transaction->finalizedMembership)
                                            <a href="{{ route('memberships.show', $transaction->finalizedMembership) }}" class="text-gray-900">Membership</a>
                                        @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                        @include('shared.table-empty', ['cols' => 7])
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $transactions->links() }}</div>
                    </div>
                </x-app-layout>
