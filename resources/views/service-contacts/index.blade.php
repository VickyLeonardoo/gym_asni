<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Kontak Serviss</h2>
    </x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <form method="GET" class="flex gap-2">
                <input name="search" value="{{ request('search') }}" placeholder="Cari kontak" class="rounded-md border-gray-300 text-sm">
                <button class="rounded-md border px-3 text-sm">
                    <i class="bi bi-search"></i>
                </button>
            </form>
            <a href="{{ route('service-contacts.create') }}" class="inline-flex items-center gap-2 rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">
                <i class="bi bi-plus-lg"></i> Contact</a>
        </div>
        <div class="overflow-hidden rounded-md border bg-white">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left">
                    <tr>
                        <th class="px-4 py-3">Nama</th>
                        <th>No. HP</th>
                        <th>Email</th>
                        <th>Aset</th>
                        <th>Maintenance</th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($contacts as $contact)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $contact->name }}</td>
                            <td>{{ $contact->phone ?: '-' }}</td>
                            <td>{{ $contact->email ?: '-' }}</td>
                            <td>{{ $contact->assets_count }}</td>
                            <td>{{ $contact->maintenances_count }}</td>
                            <td class="px-4 text-right">
                                <a href="{{ route('service-contacts.edit', $contact) }}" class="text-gray-900">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form method="POST" action="{{ route('service-contacts.destroy', $contact) }}" class="inline ms-3" onsubmit="return confirm('Hapus kontak servis ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-700">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                            @include('shared.table-empty', ['cols' => 6]) @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $contacts->links() }}</div>
            </div>
        </x-app-layout>
