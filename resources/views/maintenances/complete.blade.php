<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Selesaikan Maintenance</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('maintenances.complete', $maintenance) }}" class="space-y-4 rounded-md border bg-white p-6">
            @csrf
            @method('PATCH')

            <p class="text-sm text-gray-600">{{ $maintenance->asset->name }} — {{ $maintenance->description }}</p>

            <div>
                <label class="block text-sm font-medium">Tanggal Selesai</label>
                <input type="date" name="completed_at" value="{{ old('completed_at', now()->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border-gray-300" required>
                <x-input-error :messages="$errors->get('completed_at')" class="mt-1" />
            </div>

            <div>
                <label class="block text-sm font-medium">Biaya Aktual</label>
                <input type="number" min="0" step="0.01" name="cost" value="{{ old('cost') }}" class="mt-1 w-full rounded-md border-gray-300" required>
                <x-input-error :messages="$errors->get('cost')" class="mt-1" />
            </div>

            <div>
                <label class="block text-sm font-medium">Hasil / Resolusi</label>
                <textarea name="resolution" class="mt-1 w-full rounded-md border-gray-300" required>{{ old('resolution') }}</textarea>
                <x-input-error :messages="$errors->get('resolution')" class="mt-1" />
            </div>

            <div class="flex gap-3">
                <button class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">Selesaikan Maintenance</button>
                <a href="{{ route('assets.show', $maintenance->asset) }}" class="px-4 py-2 text-sm text-gray-600">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
