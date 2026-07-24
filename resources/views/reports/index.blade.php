<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Laporan</h2>
    </x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach([['Member',$memberCount],['Aktif',$activeCount],['Kedaluwarsa',$expiredCount],['Pendapatan','Rp '.number_format($revenue,0,',','.')],['Aset',$assetCount],['Maintenance',$maintenanceCount]] as [$label,$value])
                <div class="rounded-md border bg-white p-5">
                    <p class="text-sm text-gray-500">{{ $label }}</p>
                    <p class="mt-2 text-2xl font-semibold">{{ $value }}</p>
                </div>
            @endforeach
        </div>
        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-md border bg-white p-5">
                <h3 class="font-semibold">Aset Berdasarkan Status</h3>
                <div class="mt-4 space-y-2">
                    @foreach($assetStatuses as $status)
                        <div class="flex justify-between text-sm">
                            <span>{{ $status->label() }}</span>
                            <span>{{ $assetsByStatus[$status->value] ?? 0 }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="rounded-md border bg-white p-5">
                <h3 class="font-semibold">Aset Berdasarkan Kondisi</h3>
                <div class="mt-4 space-y-2">
                    @foreach($assetConditions as $condition)
                        <div class="flex justify-between text-sm">
                            <span>{{ $condition->label() }}</span>
                            <span>{{ $assetsByCondition[$condition->value] ?? 0 }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
