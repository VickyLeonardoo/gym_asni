<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-gray-800">Users</h2></x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <form class="flex gap-2" method="GET">
                <input name="search" value="{{ request('search') }}" placeholder="Search users" class="rounded-md border-gray-300 text-sm">
                <select name="role" class="rounded-md border-gray-300 text-sm">
                    <option value="">All roles</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->value }}" @selected(request('role') === $role->value)>{{ $role->label() }}</option>
                    @endforeach
                </select>
                <button class="rounded-md border px-3 text-sm"><i class="bi bi-search"></i></button>
            </form>
            <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white"><i class="bi bi-plus-lg"></i> User</a>
        </div>
        <div class="overflow-hidden rounded-md border bg-white">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-gray-600"><tr><th class="px-4 py-3">Name</th><th>Email</th><th>Role</th><th>Status</th><th></th></tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($users as $user)
                        <tr><td class="px-4 py-3 font-medium">{{ $user->name }}</td><td>{{ $user->email }}</td><td>{{ $user->role->label() }}</td><td>{{ $user->is_active ? 'Active' : 'Inactive' }}</td><td class="px-4 text-right"><a href="{{ route('users.edit', $user) }}" class="text-gray-700"><i class="bi bi-pencil-square"></i></a></td></tr>
                    @empty
                        @include('shared.table-empty', ['cols' => 5])
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $users->links() }}</div>
    </div>
</x-app-layout>
