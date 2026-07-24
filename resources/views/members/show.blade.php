<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">{{ $member->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('transactions.create', ['type' => 'renewal', 'member_id' => $member]) }}" class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">
                    <i class="bi bi-arrow-repeat"></i> Perpanjang</a>
                <a href="{{ route('members.edit', $member) }}" class="rounded-md border px-4 py-2 text-sm">Edit</a>
                <form method="POST" action="{{ route('members.destroy', $member) }}" onsubmit="return confirm('Arsipkan member ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="rounded-md border border-red-200 px-4 py-2 text-sm text-red-700">Arsip</button>
                </form>
            </div>
        </div>
    </x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        @if (session('status'))
            <div class="rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('status') }}</div>
        @endif
        <div class="rounded-md border bg-white p-6 grid gap-4 sm:grid-cols-3 text-sm">
            <div>
                <span class="text-gray-500">Kode</span>
                <p class="font-medium">{{ $member->member_code }}</p>
            </div>
            <div>
                <span class="text-gray-500">No. HP</span>
                <p class="font-medium">{{ $member->phone }}</p>
            </div>
            <div>
                <span class="text-gray-500">Email</span>
                <p class="font-medium">{{ $member->email ?: '-' }}</p>
            </div>
            <div>
                <span class="text-gray-500">Tanggal Lahir</span>
                <p class="font-medium">{{ $member->date_of_birth?->format('d M Y') ?: '-' }}</p>
            </div>
            <div>
                <span class="text-gray-500">Gender</span>
                <p class="font-medium">{{ $member->gender ? ucfirst($member->gender) : '-' }}</p>
            </div>
            <div class="sm:col-span-3">
                <span class="text-gray-500">Alamat</span>
                <p class="font-medium">{{ $member->address ?: '-' }}</p>
            </div>
            <div class="sm:col-span-3">
                <span class="text-gray-500">Kontak Darurat</span>
                <p class="font-medium whitespace-pre-line">{{ $member->emergency_contact ?: '-' }}</p>
            </div>
        </div>
        <div class="rounded-md border bg-white overflow-hidden">
            <div class="px-4 py-3 font-semibold">Riwayat Membership</div>
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-gray-600">
                    <tr>
                        <th class="px-4 py-3">Paket</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($member->memberships->sortByDesc('expires_at') as $membership)
                        <tr>
                            <td class="px-4 py-3">{{ $membership->plan->name }}</td>
                            <td>{{ $membership->starts_at->format('d M Y') }} - {{ $membership->expires_at->format('d M Y') }}</td>
                            <td>{{ $membership->status->label() }}</td>
                            <td>{{ $membership->payments->last()?->status->label() ?? '-' }}</td>
                            <td class="px-4 text-right">
                                <a href="{{ route('memberships.show', $membership) }}">Lihat</a>
                            </td>
                        </tr>
                    @empty
                            @include('shared.table-empty', ['cols' => 5])
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-app-layout>
