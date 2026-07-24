@csrf
<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="block text-sm font-medium">Nama</label>
        <input name="name" value="{{ old('name', $contact->name ?? '') }}" class="mt-1 w-full rounded-md border-gray-300" required>
    </div>
    <div>
        <label class="block text-sm font-medium">No. HP</label>
        <input name="phone" value="{{ old('phone', $contact->phone ?? '') }}" class="mt-1 w-full rounded-md border-gray-300">
    </div>
    <div>
        <label class="block text-sm font-medium">Email</label>
        <input type="email" name="email" value="{{ old('email', $contact->email ?? '') }}" class="mt-1 w-full rounded-md border-gray-300">
    </div>
    <div class="sm:col-span-2">
        <label class="block text-sm font-medium">Alamat</label>
        <textarea name="address" class="mt-1 w-full rounded-md border-gray-300">{{ old('address', $contact->address ?? '') }}
        </textarea>
    </div>
</div>
<div class="mt-6 flex items-center gap-3">
    <button class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">Simpan Kontak</button>
    <a href="{{ route('service-contacts.index') }}" class="text-sm text-gray-600">Batal</a>
</div>
