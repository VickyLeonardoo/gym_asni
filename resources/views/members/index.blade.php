<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Member</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <form method="GET" class="flex flex-wrap gap-2">
                <input
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari member"
                    class="min-w-56 rounded-md border-gray-300 text-sm"
                >

                <select name="status" class="rounded-md border-gray-300 text-sm">
                    <option value="">Semua</option>
                    <option value="expired" @selected(request('status') === 'expired')>Kedaluwarsa</option>
                </select>

                <button class="inline-flex items-center gap-2 rounded-md border px-3 py-2 text-sm text-gray-700">
                    <i class="bi bi-search"></i>
                    Cari
                </button>
            </form>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('members.archived') }}" class="inline-flex items-center gap-2 rounded-md border px-4 py-2 text-sm font-semibold text-gray-700">
                    <i class="bi bi-archive"></i>
                    Arsip
                </a>

                <a href="{{ route('transactions.create', ['type' => 'registration']) }}" class="inline-flex items-center gap-2 rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">
                    <i class="bi bi-plus-lg"></i>
                    Transaksi
                </a>
            </div>
        </div>

        <div class="overflow-x-auto rounded-md border bg-white">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Kode</th>
                        <th class="px-4 py-3">Member</th>
                        <th class="px-4 py-3">No. HP</th>
                        <th class="px-4 py-3">Membership Terakhir</th>
                        <th class="px-4 py-3">Berakhir</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($members as $member)
                        @php($membership = $member->latestMembership)

                        <tr class="hover:bg-gray-50">
                            <td class="whitespace-nowrap px-4 py-4 font-mono text-xs text-gray-600">
                                {{ $member->member_code }}
                            </td>
                            <td class="px-4 py-4">
                                <a href="{{ route('members.show', $member) }}" class="font-semibold text-gray-900 hover:text-gray-700">
                                    {{ $member->name }}
                                </a>
                                <p class="mt-1 text-xs text-gray-500">{{ $member->email ?: '-' }}</p>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-gray-700">
                                {{ $member->phone }}
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-medium text-gray-800">
                                        {{ $membership?->plan?->name ?? '-' }}
                                    </span>

                                    @if ($membership)
                                        <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                                            {{ $membership->status->label() }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-gray-700">
                                {{ $membership?->expires_at?->format('d M Y') ?? '-' }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('members.edit', $member) }}" class="inline-flex items-center gap-1 rounded-md border px-2.5 py-1.5 text-xs font-medium text-gray-700">
                                        <i class="bi bi-pencil-square"></i>
                                        Edit
                                    </a>

                                    <a href="{{ route('transactions.create', ['type' => 'renewal', 'member_id' => $member]) }}" class="inline-flex items-center gap-1 rounded-md border px-2.5 py-1.5 text-xs font-medium text-gray-700">
                                        <i class="bi bi-arrow-repeat"></i>
                                        Perpanjang
                                    </a>

                                    <form method="POST" action="{{ route('members.destroy', $member) }}" onsubmit="return confirm('Arsipkan member ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button class="inline-flex items-center gap-1 rounded-md border border-red-200 px-2.5 py-1.5 text-xs font-medium text-red-700">
                                            <i class="bi bi-archive"></i>
                                            Arsip
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        @include('shared.table-empty', ['cols' => 6])
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $members->links() }}</div>
    </div>
</x-app-layout>
