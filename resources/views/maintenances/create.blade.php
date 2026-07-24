<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Schedule Maintenance - {{ $asset->name }}
        </h2>
    </x-slot>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('maintenances.store', $asset) }}" class="rounded-md border bg-white p-6 space-y-4">
            @csrf<div>
                <label class="block text-sm font-medium">Kontak Servis</label>
                <select name="service_contact_id" class="mt-1 w-full rounded-md border-gray-300">
                    <option value="">Pilih</option>
                    @foreach($contacts as $contact)
                        <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Tanggal Jadwal</label>
                <input type="date" name="scheduled_at" value="{{ old('scheduled_at', now()->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border-gray-300" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Deskripsi</label>
                <textarea name="description" class="mt-1 w-full rounded-md border-gray-300" required>{{ old('description') }}
                </textarea>
            </div>
            <p class="text-xs text-gray-500">Biaya aktual dicatat setelah pekerjaan maintenance selesai.</p>
            <button class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">Schedule</button>
        </form>
    </div>
</x-app-layout>
