<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Maintenance</h2>
    </x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="GET" class="mb-4 flex gap-2">
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
                        <th class="px-4 py-3">Aset</th>
                        <th>Terjadwal</th>
                        <th>Selesai</th>
                        <th>Status</th>
                        <th>Biaya</th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($maintenances as $maintenance)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $maintenance->asset->name }}</td>
                            <td>{{ $maintenance->scheduled_at->format('d M Y') }}</td>
                            <td>{{ $maintenance->completed_at?->format('d M Y') ?? '-' }}</td>
                            <td>{{ $maintenance->status->label() }}</td>
                            <td>{{ $maintenance->status === \App\Enums\MaintenanceStatus::Completed ? 'Rp '.number_format($maintenance->cost, 0, ',', '.') : '-' }}</td>
                            <td class="px-4 text-right">
                                @if($maintenance->status !== \App\Enums\MaintenanceStatus::Completed)
                                    <a href="{{ route('maintenances.complete-form', $maintenance) }}" class="text-emerald-700">Selesaikan</a>
                                @endif
                                <a href="{{ route('maintenances.edit', $maintenance) }}" class="ms-3">Edit</a>
                            </td>
                        </tr>
                    @empty
                            @include('shared.table-empty', ['cols' => 6]) @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $maintenances->links() }}</div>
            </div>
        </x-app-layout>
