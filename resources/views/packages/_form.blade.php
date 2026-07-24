@csrf

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
        <input id="name" name="name" value="{{ old('name', $package->name) }}" class="mt-1 w-full rounded-md border-gray-300 text-sm" required>
        @error('name')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
<div>
    <label for="duration_days" class="block text-sm font-medium text-gray-700">Durasi Hari</label>
    <input id="duration_days" type="number" min="1" name="duration_days" value="{{ old('duration_days', $package->duration_days) }}" class="mt-1 w-full rounded-md border-gray-300 text-sm" required>
    @error('duration_days')
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
</div>
</div>
<div>
    <label for="price" class="block text-sm font-medium text-gray-700">Harga</label>
    <input id="price" type="number" min="0" step="0.01" name="price" value="{{ old('price', $package->price) }}" class="mt-1 w-full rounded-md border-gray-300 text-sm" required>
    @error('price')
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
</div>
<div>
    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
    <textarea id="description" name="description" rows="4" class="mt-1 w-full rounded-md border-gray-300 text-sm">{{ old('description', $package->description) }}
    </textarea>
    @error('description')
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
</div>
<label for="is_active" class="inline-flex items-center gap-2 text-sm font-medium text-gray-700">
    <input id="is_active" type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-gray-900" @checked(old('is_active', $package->is_active ?? true))>
    <span>Paket aktif</span>
</label>
<div class="flex items-center gap-3">
    <button class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">
        {{ $submit ?? 'Simpan Paket' }}
    </button>
    <a href="{{ route('packages.index') }}" class="rounded-md border px-4 py-2 text-sm font-semibold text-gray-700">Batal</a>
</div>
