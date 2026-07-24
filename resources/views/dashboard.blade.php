<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Dashboard</h2>
            <span class="text-sm text-gray-500">{{ now()->format('d M Y') }}</span>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['label' => 'Member', 'value' => $total_members, 'icon' => 'bi-people'],
                ['label' => 'Membership Aktif', 'value' => $active_memberships, 'icon' => 'bi-person-check'],
                ['label' => 'Membership Kedaluwarsa', 'value' => $expired_memberships, 'icon' => 'bi-person-x'],
                ['label' => 'Transaksi Menunggu', 'value' => $pending_transactions, 'icon' => 'bi-receipt'],
                ['label' => 'Aset', 'value' => $total_assets, 'icon' => 'bi-box-seam'],
                ['label' => 'Dalam Maintenance', 'value' => $maintenance_assets, 'icon' => 'bi-tools'],
                ['label' => 'Aset Buruk/Rusak', 'value' => $poor_assets, 'icon' => 'bi-exclamation-triangle'],
                ['label' => 'Maintenance Mendatang', 'value' => $upcoming_maintenances, 'icon' => 'bi-calendar-check'],
                ] as $card)
                <div class="rounded-md border border-gray-200 bg-white p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">{{ $card['label'] }}</p>
                            <p class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($card['value']) }}</p>
                        </div>
                        <i class="bi {{ $card['icon'] }} text-2xl text-gray-400"></i>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-md border border-gray-200 bg-white p-5">
                <h3 class="font-semibold text-gray-900">Membership Segera Habis</h3>
                <div class="mt-4 divide-y divide-gray-100">
                    @forelse ($expiring_memberships as $membership)
                        <a href="{{ route('memberships.show', $membership) }}" class="flex items-center justify-between py-3 text-sm">
                            <span class="font-medium text-gray-800">{{ $membership->member->name }}</span>
                            <span class="text-gray-500">{{ $membership->expires_at->format('d M Y') }}</span>
                        </a>
                    @empty
                            <p class="py-6 text-sm text-gray-500">Tidak ada membership yang segera habis.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-md border border-gray-200 bg-white p-5">
                    <h3 class="font-semibold text-gray-900">Aset Perlu Perhatian</h3>
                    <div class="mt-4 divide-y divide-gray-100">
                        @forelse ($assets_needing_attention as $asset)
                            <a href="{{ route('assets.show', $asset) }}" class="flex items-center justify-between py-3 text-sm">
                                <span class="font-medium text-gray-800">{{ $asset->name }}</span>
                                <span class="text-gray-500">{{ $asset->condition->label() }} / {{ $asset->status->label() }}</span>
                            </a>
                        @empty
                                <p class="py-6 text-sm text-gray-500">Semua aset terlihat stabil.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </x-app-layout>
