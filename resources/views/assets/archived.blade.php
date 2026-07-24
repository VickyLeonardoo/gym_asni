<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Arsip Aset</h2>
    </x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('status') }}</div>
        @endif
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <form method="GET" class="flex flex-wrap gap-2">
                <input name="search" value="{{ request('search') }}" placeholder="Cari arsip aset" class="rounded-md border-gray-300 text-sm">
                <select name="status" class="rounded-md border-gray-300 text-sm">
                    <option value="">Semua status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
                    @endforeach
                </select>
                <select name="condition" class="rounded-md border-gray-300 text-sm">
                    <option value="">Semua kondisi</option>
                    @foreach($conditions as $condition)
                        <option value="{{ $condition->value }}" @selected(request('condition') === $condition->value)>{{ $condition->label() }}</option>
                    @endforeach
                </select>
                <button class="rounded-md border px-3 text-sm">
                    <i class="bi bi-search"></i>
                </button>
            </form>
            <a href="{{ route('assets.index') }}" class="inline-flex items-center gap-2 rounded-md border px-4 py-2 text-sm font-semibold text-gray-700">
                <i class="bi bi-arrow-left"></i> Aset Aktif</a>
        </div>
        <div class="overflow-hidden rounded-md border bg-white">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left">
                    <tr>
                        <th class="px-4 py-3">Kode</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Kondisi</th>
                        <th>Diarsipkan</th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($assets as $asset)
                        <tr>
                            <td class="px-4 py-3">{{ $asset->asset_code }}</td>
                            <td class="font-medium">{{ $asset->name }}
                                <p class="text-xs font-normal text-gray-500">{{ $asset->brand ?: '-' }}</p>
                            </td>
                            <td>{{ $asset->category }}</td>
                            <td>{{ $asset->status->label() }}</td>
                            <td>{{ $asset->condition->label() }}</td>
                            <td>{{ $asset->deleted_at?->format('d M Y') ?? '-' }}</td>
                            <td class="px-4 text-right">
                                <form method="POST" action="{{ route('assets.restore', $asset->id) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-emerald-700">
                                        <i class="bi bi-arrow-counterclockwise"></i> Pulihkan</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                            @include('shared.table-empty', ['cols' => 7]) @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $assets->links() }}</div>
            </div>
        </x-app-layout>
