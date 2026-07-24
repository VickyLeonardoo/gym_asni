<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Update Maintenance</h2>
    </x-slot>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('maintenances.update', $maintenance) }}" class="rounded-md border bg-white p-6 space-y-4">
            @csrf
            @method('PUT')
            <p class="text-sm text-gray-600">{{ $maintenance->asset->name }}</p>
            <div>
                <label class="block text-sm font-medium">Kontak Servis</label>
                <select name="service_contact_id" class="mt-1 w-full rounded-md border-gray-300">
                    <option value="">Pilih</option>
                    @foreach($contacts as $contact)
                        <option value="{{ $contact->id }}" @selected(old('service_contact_id', $maintenance->service_contact_id) == $contact->id)>{{ $contact->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div data-completion-field>
                    <label class="block text-sm font-medium">Tanggal Jadwal</label>
                    <input type="date" name="scheduled_at" value="{{ old('scheduled_at', $maintenance->scheduled_at->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border-gray-300" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Tanggal Selesai</label>
                    <input type="date" name="completed_at" value="{{ old('completed_at', $maintenance->completed_at?->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border-gray-300">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium">Status</label>
                <select name="status" class="mt-1 w-full rounded-md border-gray-300">
                    @foreach($statuses as $status)
                        <option value="{{ $status->value }}" @selected(old('status', $maintenance->status->value) === $status->value)>{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div data-completion-field>
                <label class="block text-sm font-medium">Biaya</label>
                <input type="number" step="0.01" name="cost" value="{{ old('cost', $maintenance->cost) }}" class="mt-1 w-full rounded-md border-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium">Deskripsi</label>
                <textarea name="description" class="mt-1 w-full rounded-md border-gray-300" required>{{ old('description', $maintenance->description) }}
                </textarea>
            </div>
            <div data-completion-field>
                <label class="block text-sm font-medium">Resolusi</label>
                <textarea name="resolution" class="mt-1 w-full rounded-md border-gray-300">{{ old('resolution', $maintenance->resolution) }}
                </textarea>
            </div>
            <button class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">Perbarui</button>
        </form>
    </div>
    <script>
        (() => {
            const status = document.querySelector('select[name="status"]');
            const fields = document.querySelectorAll('[data-completion-field]');
            const sync = () => fields.forEach((field) => field.hidden = status.value !== 'completed');
            status.addEventListener('change', sync);
            sync();
        })();
    </script>
</x-app-layout>
