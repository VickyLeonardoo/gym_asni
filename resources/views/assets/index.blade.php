<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Aset</h2>
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
                    placeholder="Cari aset"
                    class="rounded-md border-gray-300 text-sm"
                >

                <select name="status" class="rounded-md border-gray-300 text-sm">
                    <option value="">Semua status</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->value }}" @selected(request('status') === $status->value)>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>

                <select name="condition" class="rounded-md border-gray-300 text-sm">
                    <option value="">Semua kondisi</option>
                    @foreach ($conditions as $condition)
                        <option value="{{ $condition->value }}" @selected(request('condition') === $condition->value)>
                            {{ $condition->label() }}
                        </option>
                    @endforeach
                </select>

                <button class="rounded-md border px-3 text-sm">
                    <i class="bi bi-search"></i>
                </button>
            </form>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('assets.archived') }}" class="inline-flex items-center gap-2 rounded-md border px-4 py-2 text-sm font-semibold text-gray-700">
                    <i class="bi bi-archive"></i>
                    Arsip
                </a>

                <a href="{{ route('assets.create') }}" class="inline-flex items-center gap-2 rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">
                    <i class="bi bi-plus-lg"></i>
                    Aset
                </a>
            </div>
        </div>

        <div class="overflow-x-auto rounded-md border bg-white">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left">
                    <tr>
                        <th class="px-4 py-3">Kode</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Kondisi</th>
                        <th>Maintenance Terakhir</th>
                        <th>Jatuh Tempo</th>
                        <th class="px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($assets as $asset)
                        @php($nextDue = $asset->nextMaintenanceDueDate())

                        <tr>
                            <td class="px-4 py-3">{{ $asset->asset_code }}</td>
                            <td class="font-medium">
                                <a href="{{ route('assets.show', $asset) }}">{{ $asset->name }}</a>
                                <p class="text-xs font-normal text-gray-500">
                                    Setiap {{ $asset->maintenance_interval_months }} bulan
                                </p>
                            </td>
                            <td>{{ $asset->category }}</td>
                            <td>{{ $asset->status->label() }}</td>
                            <td>{{ $asset->condition->label() }}</td>
                            <td>{{ $asset->lastMaintenanceDate()?->format('d M Y') ?? '-' }}</td>
                            <td>
                                @if ($nextDue)
                                    <span class="{{ $nextDue->isPast() ? 'text-red-700 font-semibold' : ($nextDue->diffInDays(now()) <= 30 ? 'text-amber-700 font-semibold' : '') }}">
                                        {{ $nextDue->format('d M Y') }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 text-right">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('assets.edit', $asset) }}" class="text-gray-900">
                                        <i class="bi bi-pencil-square"></i>
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('assets.destroy', $asset) }}" onsubmit="return confirm('Arsipkan aset ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button class="text-red-700">
                                            <i class="bi bi-archive"></i>
                                            Arsip
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        @include('shared.table-empty', ['cols' => 8])
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $assets->links() }}</div>
    </div>
</x-app-layout>
