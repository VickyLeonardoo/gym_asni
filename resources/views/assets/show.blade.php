<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">{{ $asset->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('maintenances.create', $asset) }}" class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">
                    <i class="bi bi-tools"></i> Maintenance</a>
                <a href="{{ route('assets.edit', $asset) }}" class="rounded-md border px-4 py-2 text-sm">Edit</a>
                <form method="POST" action="{{ route('assets.destroy', $asset) }}" onsubmit="return confirm('Arsipkan aset ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="rounded-md border border-red-200 px-4 py-2 text-sm text-red-700">Arsip</button>
                </form>
            </div>
        </div>
    </x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        @php($nextDue = $asset->nextMaintenanceDueDate())
        <div class="rounded-md border bg-white p-6 grid gap-4 sm:grid-cols-4 text-sm">
            <div>
                <span class="text-gray-500">Kode</span>
                <p class="font-medium">{{ $asset->asset_code }}</p>
            </div>
            <div>
                <span class="text-gray-500">Kategori</span>
                <p>{{ $asset->category }}</p>
            </div>
            <div>
                <span class="text-gray-500">Status</span>
                <p>{{ $asset->status->label() }}</p>
            </div>
            <div>
                <span class="text-gray-500">Kondisi</span>
                <p>{{ $asset->condition->label() }}</p>
            </div>
            <div>
                <span class="text-gray-500">Toko</span>
                <p>{{ $asset->purchaseStore->name ?? '-' }}</p>
            </div>
            <div>
                <span class="text-gray-500">Supplier</span>
                <p>{{ $asset->supplier->name ?? '-' }}</p>
            </div>
            <div>
                <span class="text-gray-500">Servis</span>
                <p>{{ $asset->serviceContact->name ?? '-' }}</p>
            </div>
            <div>
                <span class="text-gray-500">Lokasi</span>
                <p>{{ $asset->location ?? '-' }}</p>
            </div>
            <div>
                <span class="text-gray-500">Interval Maintenance</span>
                <p>{{ $asset->maintenance_interval_months }} bulan</p>
            </div>
            <div>
                <span class="text-gray-500">Maintenance Terakhir</span>
                <p>{{ $asset->lastMaintenanceDate()?->format('d M Y') ?? '-' }}</p>
            </div>
            <div>
                <span class="text-gray-500">Jatuh Tempo</span>
                <p class="{{ $nextDue?->isPast() ? 'text-red-700 font-semibold' : ($nextDue && $nextDue->diffInDays(now())
                    <= 30 ? 'text-amber-700 font-semibold' : '') }}">{{ $nextDue?->format('d M Y') ?? '-' }}
                </p>
            </div>
            <div>
                <span class="text-gray-500">Garansi Berakhir</span>
                <p>{{ $asset->warranty_expires_at?->format('d M Y') ?? '-' }}</p>
            </div>
        </div>
        <div class="rounded-md border bg-white overflow-hidden">
            <div class="px-4 py-3 font-semibold">Riwayat Maintenance</div>
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left">
                    <tr>
                        <th class="px-4 py-3">Terjadwal</th>
                        <th>Selesai</th>
                        <th>Status</th>
                        <th>Biaya</th>
                        <th>Deskripsi</th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($asset->maintenances as $maintenance)
                        <tr>
                            <td class="px-4 py-3">{{ $maintenance->scheduled_at->format('d M Y') }}</td>
                            <td>{{ $maintenance->completed_at?->format('d M Y') ?? '-' }}</td>
                            <td>{{ $maintenance->status->label() }}</td>
                            <td>{{ $maintenance->status === \App\Enums\MaintenanceStatus::Completed ? 'Rp '.number_format($maintenance->cost, 0, ',', '.') : '-' }}</td>
                            <td>{{ str($maintenance->description)->limit(80) }}</td>
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
                <div class="rounded-md border bg-white overflow-hidden">
                    <div class="px-4 py-3 font-semibold">Monitoring Kondisi</div>
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-left">
                            <tr>
                                <th class="px-4 py-3">Tanggal</th>
                                <th>Kondisi</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($asset->conditionLogs->sortByDesc('created_at') as $log)
                                <tr>
                                    <td class="px-4 py-3">{{ $log->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $log->condition->label() }}</td>
                                    <td>{{ $log->notes ?: '-' }}</td>
                                </tr>
                            @empty
                                    @include('shared.table-empty', ['cols' => 3]) @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </x-app-layout>
