@csrf
<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="block text-sm font-medium text-gray-700">Nama</label>
        <input name="name" value="{{ old('name', $managedUser->name ?? '') }}" class="mt-1 w-full rounded-md border-gray-300" required>
        <x-input-error :messages="$errors->get('name')" class="mt-1" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" value="{{ old('email', $managedUser->email ?? '') }}" class="mt-1 w-full rounded-md border-gray-300" required>
        <x-input-error :messages="$errors->get('email')" class="mt-1" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Role</label>
        <select name="role" class="mt-1 w-full rounded-md border-gray-300" required>
            @foreach ($roles as $role)
                <option value="{{ $role->value }}" @selected(old('role', isset($managedUser) ? $managedUser->role->value : '') === $role->value)>{{ $role->label() }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Status</label>
        <select name="is_active" class="mt-1 w-full rounded-md border-gray-300" required>
            <option value="1" @selected((string) old('is_active', isset($managedUser) ? (int) $managedUser->is_active : 1) === '1')>Aktif</option>
            <option value="0" @selected((string) old('is_active', isset($managedUser) ? (int) $managedUser->is_active : 1) === '0')>Nonaktif</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password" class="mt-1 w-full rounded-md border-gray-300" {{ isset($managedUser) ? '' : 'required' }}>
        <x-input-error :messages="$errors->get('password')" class="mt-1" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="mt-1 w-full rounded-md border-gray-300" {{ isset($managedUser) ? '' : 'required' }}>
    </div>
</div>
<div class="mt-6 flex items-center gap-3">
    <button class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">Simpan Pengguna</button>
    <a href="{{ route('users.index') }}" class="text-sm text-gray-600">Batal</a>
</div>
