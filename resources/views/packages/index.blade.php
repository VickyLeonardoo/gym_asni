<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Paket</h2>
            <a href="{{ route('packages.create') }}" class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">
                <i class="bi bi-plus-lg"></i> Tambah Paket
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('status') }}</div>
        @endif

        <form method="GET" class="mb-4 flex flex-wrap gap-2">
            <input name="search" value="{{ request('search') }}" placeholder="Cari paket" class="rounded-md border-gray-300 text-sm">
            <select name="status" class="rounded-md border-gray-300 text-sm">
                <option value="">Semua status</option>
                <option value="active" @selected(request('status') === 'active')>Aktif</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
            </select>
            <button class="rounded-md border px-3 text-sm"><i class="bi bi-search"></i></button>
        </form>

        <div class="overflow-hidden rounded-md border bg-white">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-gray-600">
                    <tr>
                        <th class="px-4 py-3">Paket</th>
                        <th>Durasi</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Dipakai</th>
                        <th class="px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($packages as $package)
                        <tr>
                            <td class="px-4 py-3">
                                <p class="font-medium text-gray-900">{{ $package->name }}</p>
                                <p class="mt-1 max-w-md text-xs text-gray-500">{{ $package->description ?: '-' }}</p>
                            </td>
                            <td>{{ number_format($package->duration_days) }} hari</td>
                            <td>Rp {{ number_format((float) $package->price, 0, ',', '.') }}</td>
                            <td>
                                <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $package->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $package->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>{{ $package->memberships_count }}</td>
                            <td class="px-4">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('packages.edit', $package) }}" class="font-medium text-gray-900">Edit</a>
                                    <form method="POST" action="{{ route('packages.destroy', $package) }}" onsubmit="return confirm('Hapus paket ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="font-medium text-red-600">Hapus</button>
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

            <div class="mt-4">{{ $packages->links() }}</div></div>
    </x-app-layout>
